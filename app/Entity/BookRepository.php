<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends EntityRepository {

	const FIELD_SEARCH_SEPARATOR = ':';

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
			'separator' => self::FIELD_SEARCH_SEPARATOR,
		];
	}

	public static function getStructuredSearchQuery($searchQuery) {
		if (strpos($searchQuery, self::FIELD_SEARCH_SEPARATOR) !== false) {
			list($field, $term) = explode(self::FIELD_SEARCH_SEPARATOR, $searchQuery);
		} else {
			$field = '';
			$term = $searchQuery;
		}
		$structure = new \stdClass();
		$structure->raw = $searchQuery;
		$structure->field = trim($field);
		$structure->term = trim($term);
		$structure->normalized = Book::normalizedFieldValue($field, $term);
		return $structure;
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
	 * @param string $query
	 * @param string $sort
	 * @return QueryBuilder
	 */
	public function filterByQuery($query, $sort = null) {
		$alias = 'b';
		$qb = $this->createQueryBuilder($alias);
		if (empty($sort)) {
			$sort = 'title';
		}
		foreach (explode(',', $sort) as $orderBy) {
			$orderBy = ltrim($orderBy);
			if (strpos($orderBy, '-') === false) {
				$field = $orderBy;
				$order = 'asc';
			} else {
				list($field, $order) = explode('-', ltrim($orderBy));
			}
			if (in_array($field, self::$sortableFields)) {
				$qb->addOrderBy("$alias.$field", $order);
			}
		}
		if (empty($query)) {
			return $qb;
		}
		if (strpos($query, self::FIELD_SEARCH_SEPARATOR) !== false) {
			list($searchField, $fieldQuery) = explode(self::FIELD_SEARCH_SEPARATOR, $query);
			$fieldQuery = trim($fieldQuery);
			$allowedFields = self::$searchableFields;
			if (in_array($searchField, $allowedFields)) {
				if ($searchField == 'isbn') {
					$searchField = 'isbnClean';
				}
				if ($fieldQuery[0] === '"') {
					$operator = '=';
					$fieldQuery = trim($fieldQuery, '"');
				} else {
					$operator = 'LIKE';
					$fieldQuery = '%'.Book::normalizedFieldValue($searchField, $fieldQuery).'%';
				}
				$qb->where("$alias.$searchField $operator ?1");
				if (isset(self::$linkedSearchableFields[$searchField])) {
					foreach (self::$linkedSearchableFields[$searchField] as $linkedSearchableField) {
						$qb->orWhere("$alias.$linkedSearchableField $operator ?1");
					}
				}
				$qb->setParameter('1', $fieldQuery);
				return $qb;
			}
		}
		if (is_numeric($query)) {
			return $qb
				->where("$alias.publishingYear = ?1")
				->setParameter('1', $query);
		}
		if (preg_match('/^(\d+)-(\d+)$/', $query, $matches)) {
			return $qb
				->where("$alias.publishingYear BETWEEN ?1 AND ?2")
				->setParameters([1 => $matches[1], 2 => $matches[2]]);
		}
		foreach (self::$globallySearchableFields as $globallySearchableField) {
			$qb->orWhere("$alias.$globallySearchableField LIKE ?1");
		}
		$qb->setParameter('1', "%$query%");
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

	/** @return \Gedmo\Tree\Entity\Repository\NestedTreeRepository */
	public function getCategoryRepository() {
		return $this->_em->getRepository(BookCategory::class);
	}

	public function revisions() {
		return $this->getRevisionRepository()->allInReverse();
	}

	/** @return BookRevisionRepository */
	public function getRevisionRepository() {
		return $this->_em->getRepository(BookRevision::class);
	}
}
