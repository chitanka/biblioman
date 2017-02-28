<?php namespace App\Entity\Query;

use App\Entity\BookCategory;
use App\Entity\Repository\BookRepository;
use App\Entity\Shelf;
use App\Library\BookField;
use App\Library\BookSearchCriteria;
use Doctrine\ORM\QueryBuilder;

class BookQuery {

	const ALIAS = 'b';

	public static $searchableFields = [
		'author',
		'title',
		'altTitle',
		'subtitle',
		'subtitle2',
		'volumeTitle',
		'sequence',
		'subsequence',
		'series',
		'translator',
		'translatedFromLanguage',
		'dateOfTranslation',
		'adaptedBy',
		'otherAuthors',
		'compiler',
		'chiefEditor',
		'managingEditor',
		'editor',
		'editorialStaff',
		'publisherEditor',
		'consultant',
		'artist',
		'illustrator',
		'artistEditor',
		'technicalEditor',
		'reviewer',
		'scienceEditor',
		'copyreader',
		'corrector',
		'layout',
		'coverLayout',
		'libraryDesign',
		'computerProcessing',
		'prepress',
		'publisher',
		'publisherCity',
		'publishingYear',
		'print',
		'contentType',
		'nationality',
		'language',
		'edition',
		'litGroup',
		'typeSettingIn',
		'printSigned',
		'printOut',
		'printerSheets',
		'publisherSheets',
		'provisionPublisherSheets',
		'format',
		'publisherCode',
		'trackingCode',
		'publisherOrder',
		'publisherNumber',
		'uniformProductClassification',
		'universalDecimalClassification',
		'binding',
		'illustrated',
		'isbn',
		'isbnClean',
		'genre',
		'themes',
		'createdBy',
		'nbScans',
		'isIncomplete',
		'reasonWhyIncomplete',
	];

	public static $sortableFields = [
		'title',
		'sequenceNr',
		'subsequenceNr',
		'seriesNr',
		'publishingYear',
		'createdAt',
		'updatedAt',
	];

	private static $globallySearchableFields = [
		'title',
		'altTitle',
		'subtitle',
		'sequence',
		'subsequence',
		'author',
		'translator',
		'otherAuthors',
		'compiler',
		'editor',
		'publisher',
	];

	private static $linkedSearchableFields = [
		'author' => ['adaptedBy', 'otherAuthors'],
		'title' => ['altTitle'],
		'isbn' => ['isbnClean'],
		'sequence' => ['subsequence', 'series'],
		'subsequence' => ['sequence'],
	];

	public static function getSearchableFieldsDefinition() {
		return [
			'fields' => self::$searchableFields,
			'separator' => BookSearchCriteria::FIELD_SEARCH_SEPARATOR,
		];
	}

	/** @var BookRepository */
	private $repository;
	/** @var QueryBuilder */
	private $qb;

	public function __construct(BookRepository $repository, BookSearchCriteria $criteria) {
		$this->repository = $repository;
		$this->qb = $this->repository->createQueryBuilder(self::ALIAS);
		$this->setSearchCriteria($criteria);
	}

	public function getQueryBuilder() {
		return $this->qb;
	}

	private function setSearchCriteria(BookSearchCriteria $criteria) {
		$this->addSort($criteria->sort);
		if ($criteria->isEmpty()) {
			return $this->qb;
		}
		$this->useShelf($criteria->shelf);
		$this->useCategory($criteria->category);
		if ($this->isFieldSearchable($criteria->field)) {
			return $this->filterBySelectedField($criteria->field, $criteria->term);
		}
		if (is_numeric($criteria->term)) {
			return $this->filterByPublishingYear($criteria->term);
		}
		if (preg_match('/^(\d+)-(\d+)$/', $criteria->term, $matches)) {
			return $this->filterByPublishingYearRange($matches[1], $matches[2]);
		}
		return $this->filterGlobally($criteria->term);
	}

	private function addSort(array $sort) {
		array_walk($sort, function($order, $field) {
			if (in_array($field, self::$sortableFields)) {
				$this->qb->addOrderBy(self::ALIAS.".$field", $order);
			}
		});
	}

	private function useShelf(Shelf $shelf = null) {
		if ($shelf !== null) {
			$this->qb->join(self::ALIAS.".booksOnShelf", 'bs')->andWhere('bs.shelf = :shelf')->setParameter('shelf', $shelf);
		}
	}

	private function useCategory(BookCategory $category = null) {
		if ($category !== null) {
			$this->qb->andWhere(self::ALIAS.".category = :category")->setParameter('category', $category);
		}
	}

	private function isFieldSearchable($field) {
		return in_array($field, self::$searchableFields);
	}

	private function filterBySelectedField($field, $term) {
		if ($field == 'isbn') {
			$field = 'isbnClean';
		}
		if ($term[0] === '"') {
			$operator = '=';
			$fieldQuery = trim($term, '"');
		} else {
			$operator = 'LIKE';
			$fieldQuery = '%'.BookField::normalizedFieldValue($field, $term).'%';
		}
		$predicates = [self::ALIAS.".{$field} $operator ?1"];
		if (isset(self::$linkedSearchableFields[$field])) {
			$predicates = array_merge($predicates, array_map(function ($field) use ($operator) {
				return self::ALIAS . ".$field $operator ?1";
			}, self::$linkedSearchableFields[$field]));
		}
		$this->qb->andWhere(implode(' OR ', $predicates));
		$this->qb->setParameter('1', $fieldQuery);
		return $this->qb;
	}

	private function filterByPublishingYear($year) {
		return $this->qb
			->where(self::ALIAS.".publishingYear = ?1")
			->setParameter('1', $year);
	}

	private function filterByPublishingYearRange($firstYear, $lastYear) {
		return $this->qb
			->where(self::ALIAS.".publishingYear BETWEEN ?1 AND ?2")
			->setParameters([1 => $firstYear, 2 => $lastYear]);
	}

	private function filterGlobally($term) {
		$this->qb->andWhere(implode(' OR ', array_map(function($field) {
			return self::ALIAS.".$field LIKE ?1";
		}, self::$globallySearchableFields)));
		$this->qb->setParameter('1', "%{$term}%");
		return $this->qb;
	}
}
