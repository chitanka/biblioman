<?php namespace App\Entity\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRevision;
use App\Library\BookField;
use App\Library\BookSearchQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends EntityRepository {

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
			'separator' => BookSearchQuery::FIELD_SEARCH_SEPARATOR,
		];
	}

	/**
	 * @return QueryBuilder
	 */
	public function recent() {
		return $this->createQueryBuilder('b')
			->orderBy('b.createdAt', 'DESC');
	}

	/**
	 * @param string $title
	 * @param int $selfId
	 * @return Book[]
	 */
	public function findDuplicatesByTitle($title, $selfId) {
		return $this->createQueryBuilder('b')
			->where('b.id != ?1')->setParameter('1', $selfId)
			->andWhere('b.title = ?2')->setParameter('2', $title)
			->getQuery()
			->getResult();
	}

	/**
	 * @param BookSearchQuery $query
	 * @return QueryBuilder
	 */
	public function filterByQuery($query) {
		$alias = 'b';
		$qb = $this->createQueryBuilder($alias);
		array_walk($query->sort, function($order, $field) use ($qb, $alias) {
			if (in_array($field, self::$sortableFields)) {
				$qb->addOrderBy("$alias.$field", $order);
			}
		});
		if ($query->isEmpty()) {
			return $qb;
		}
		if ($query->shelf) {
			$qb->join("$alias.booksOnShelf", 'bs')->andWhere('bs.shelf = :shelf')->setParameter('shelf', $query->shelf);
		}
		if ($query->category) {
			$qb->andWhere("$alias.category = :category")->setParameter('category', $query->category);
		}
		if ($query->field && in_array($query->field, self::$searchableFields)) {
			if ($query->field == 'isbn') {
				$query->field = 'isbnClean';
			}
			if ($query->term[0] === '"') {
				$operator = '=';
				$fieldQuery = trim($query->term, '"');
			} else {
				$operator = 'LIKE';
				$fieldQuery = '%'.BookField::normalizedFieldValue($query->field, $query->term).'%';
			}
			$qb->andWhere("$alias.{$query->field} $operator ?1");
			if (isset(self::$linkedSearchableFields[$query->field])) {
				foreach (self::$linkedSearchableFields[$query->field] as $linkedSearchableField) {
					$qb->orWhere("$alias.$linkedSearchableField $operator ?1");
				}
			}
			$qb->setParameter('1', $fieldQuery);
			return $qb;
		}
		if (is_numeric($query->term)) {
			return $qb
				->where("$alias.publishingYear = ?1")
				->setParameter('1', $query->term);
		}
		if (preg_match('/^(\d+)-(\d+)$/', $query->term, $matches)) {
			return $qb
				->where("$alias.publishingYear BETWEEN ?1 AND ?2")
				->setParameters([1 => $matches[1], 2 => $matches[2]]);
		}
		foreach (self::$globallySearchableFields as $globallySearchableField) {
			$qb->orWhere("$alias.$globallySearchableField LIKE ?1");
		}
		$qb->setParameter('1', "%{$query->term}%");
		return $qb;
	}

	/**
	 * @param BookCategory $category
	 * @return QueryBuilder
	 */
	public function filterByCategory(BookCategory $category) {
		$qb = $this->createQueryBuilder('b')->where('b.category IN (:categories)');
		$categories = array_merge([$category], $this->getCategoryRepository()->children($category));
		$qb->setParameter('categories', $categories);
		return $qb;
	}

	/**
	 * @return QueryBuilder
	 */
	public function filterIncomplete() {
		return $this->createQueryBuilder('b')
			->where('b.isIncomplete = 1')
			->orWhere('b.nbScans = 0');
	}

	/** @return BookCategoryRepository */
	private function getCategoryRepository() {
		return $this->_em->getRepository(BookCategory::class);
	}

	public function revisions() {
		return $this->getRevisionRepository()->allInReverse();
	}

	/** @return BookRevisionRepository */
	private function getRevisionRepository() {
		return $this->_em->getRepository(BookRevision::class);
	}
}
