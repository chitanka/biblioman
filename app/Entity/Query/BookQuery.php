<?php namespace App\Entity\Query;

use App\Collection\BookMultiFields;
use App\Entity\BookCategory;
use App\Entity\BookField\BookField;
use App\Entity\Repository\BookMultiFieldRepository;
use App\Entity\Repository\BookRepository;
use App\Entity\Shelf;
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
		'printingHouse',
		'contentType',
		'nationality',
		'language',
		'edition',
		'media',
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
		'pageCount',
		'publisherOrder',
		'publisherNumber',
		'uniformProductClassification',
		'universalDecimalClassification',
		'binding',
		'price',
		'illustrated',
		'isbn',
		'isbnClean',
		'genre',
		'themes',
		'createdBy',
		'completedBy',
		'nbScans',
		'hasOnlyScans',
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
	/** @var BookMultiFieldRepository */
	private $bookMultiFieldRepository;
	/** @var QueryBuilder */
	private $qb;

	public function __construct(BookRepository $repository, BookSearchCriteria $criteria) {
		$this->repository = $repository;
		$this->bookMultiFieldRepository = $this->repository->getBookMultiFieldRepository();
		$this->qb = $this->repository->createQueryBuilder(self::ALIAS);
		$this->applySearchCriteria($criteria);
	}

	public function getQueryBuilder() {
		return $this->qb;
	}

	private function applySearchCriteria(BookSearchCriteria $criteria) {
		$this->addSort($criteria->sort);
		if ($criteria->isEmpty()) {
			return $this->qb;
		}
		return $this->setFilters($criteria);
	}

	private function setFilters(BookSearchCriteria $criteria) {
		$this->filterByShelves($criteria->shelves);
		$this->filterByCategories($criteria->categories);
		if ($this->isFieldSearchable($criteria->field)) {
			return $this->filterBySelectedField($criteria->field, $criteria->terms ?: $criteria->term);
		}
		if ($year = Year::tryCreateFromString($criteria->term)) {
			return $this->filterByPublishingYear($year);
		}
		if ($yearRange = YearRange::tryCreateFromString($criteria->term)) {
			return $this->filterByPublishingYearRange($yearRange);
		}
		return $this->filterGlobally($criteria->term);
	}

	private function addSort(array $sort) {
		array_walk($sort, function($order, $field) {
			if (in_array($field, self::$sortableFields)) {
				$this->qb->addOrderBy($this->fieldForQuery($field), $order);
			}
		});
	}

	/** @param Shelf[] $shelves */
	private function filterByShelves($shelves = null) {
		if (!empty($shelves)) {
			$this->qb->join($this->fieldForQuery('booksOnShelf'), 'bs')->andWhere('bs.shelf IN (:shelves)')->setParameter('shelves', $shelves);
		}
	}

	/** @param BookCategory[] $categories */
	private function filterByCategories($categories = null) {
		if (!empty($categories)) {
			$this->qb->andWhere($this->fieldForQuery('category')." IN (:categories)")->setParameter('categories', $categories);
		}
	}

	private function isFieldSearchable($field) {
		return in_array($field, self::$searchableFields);
	}

	private function filterBySelectedField($field, $term) {
		$predicates = $this->createSearchPredicates($field, $term);
		$orX = $this->qb->expr()->orX();
		foreach ($predicates as $field => $fdata) {
			if (!in_array($field, BookMultiFields::MULTI_FIELDS)) {
				foreach ($fdata as $idx => $fdatum) {
					list($operator, $fieldQuery) = $fdatum;
					$orX->add($this->fieldForQuery($field)." $operator ?$idx");
					$this->qb->setParameter($idx, $fieldQuery);
				}
				unset($predicates[$field]);
			}
		}
		$bmfAlias = 'bmf';
		$orMultiFields = $this->qb->expr()->orX();
		foreach ($predicates as $field => $fdata) {
			$orMultiField = $this->qb->expr()->orX();
			foreach ($fdata as $idx => $fdatum) {
				list($operator, $fieldQuery) = $fdatum;
				$orMultiField->add("$bmfAlias.value $operator :value$idx");
				$this->qb->setParameter("value$idx", $fieldQuery);
			}
			$orMultiFields->add($this->qb->expr()->andX("$bmfAlias.field = :$field", $orMultiField));
			$this->qb->setParameter($field, $field);
		}
		if ($orMultiFields->count() > 0) {
			$orX = $this->qb->expr()->orX(
				$orX,
				$this->qb->expr()->in(
					$this->fieldForQuery('id'),
					$this->bookMultiFieldRepository->createQueryBuilder($bmfAlias)
						->select("identity($bmfAlias.book)")
						->where($orMultiFields)
						->getDQL()
				)
			);
		}
		$this->qb->andWhere($orX);
		return $this->qb;
	}

	private function createSearchPredicates($field, $term) {
		$terms = is_array($term) ? $term : [$term];
		$predicates = [];
		foreach ($terms as $idx => $term) {
			if ($term[0] === '"') {
				$operator = '=';
				$fieldQuery = trim($term, '"');
			} else {
				$operator = 'LIKE';
				$fieldQuery = '%'.BookField::normalizedFieldValue($field, $term).'%';
			}
			$predicates[$field][$idx] = [$operator, $fieldQuery];
			if (isset(self::$linkedSearchableFields[$field])) {
				foreach (self::$linkedSearchableFields[$field] as $linkedSearchableField) {
					$predicates[$linkedSearchableField][$idx] = [$operator, $fieldQuery];
				}
			}
		}
		return $predicates;
	}

	private function filterByPublishingYear(Year $year) {
		return $this->qb
			->where($this->fieldForQuery('publishingYear')." = ?1")
			->setParameter('1', $year->year);
	}

	private function filterByPublishingYearRange(YearRange $range) {
		return $this->qb
			->where($this->fieldForQuery('publishingYear')." BETWEEN ?1 AND ?2")
			->setParameters([1 => $range->firstYear, 2 => $range->lastYear]);
	}

	private function filterGlobally($term) {
		$this->qb->andWhere(implode(' OR ', array_map(function($field) {
			return $this->fieldForQuery($field)." LIKE ?1";
		}, self::$globallySearchableFields)));
		$this->qb->setParameter('1', "%{$term}%");
		return $this->qb;
	}

	private function fieldForQuery($field) {
		return self::ALIAS.'.'.$field;
	}
}
