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
		'translator',
		'translatedFromLanguage',
		'dateOfTranslation',
		'otherAuthors',
		'compiler',
		'chiefEditor',
		'editor',
		'publisherEditor',
		'consultant',
		'artist',
		'artistEditor',
		'technicalEditor',
		'reviewer',
		'corrector',
		'layout',
		'coverLayout',
		'computerProcessing',
		'prepress',
		'publisher',
		'publisherCity',
		'publishingDate',
		'print',
		'contentType',
		'nationality',
		'language',
		'edition',
		'litGroup',
		'typeSettingIn',
		'printSigned',
		'printOut',
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
		'sequence',
		'publisher',
		'publisherCity',
		'publishingDate',
		'createdAt',
		'updatedAt',
	];

	private static $linkedSearchableFields = [
		'author' => ['otherAuthors'],
		'title' => ['altTitle'],
		'isbn' => ['isbnClean'],
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
	 * @param int $maxResults
	 * @return Book[]
	 */
	public function findRecent($maxResults = 5) {
		return $this->createQueryBuilder('b')
			->orderBy('b.createdAt', 'DESC')
			->setMaxResults($maxResults)
			->getQuery()
			->getResult();
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
				$qb->where("b.{$searchField} $operator ?1");
				if (isset(self::$linkedSearchableFields[$searchField])) {
					foreach (self::$linkedSearchableFields[$searchField] as $linkedSearchableField) {
						$qb->orWhere("b.{$linkedSearchableField} $operator ?1");
					}
				}
				$qb->setParameter('1', $fieldQuery);
				return $qb;
			}
		}
		if (is_numeric($query)) {
			return $qb
				->where('b.publishingDate = ?1')
				->setParameter('1', $query);
		}
		if (preg_match('/^(\d+)-(\d+)$/', $query, $matches)) {
			return $qb
				->where('b.publishingDate BETWEEN ?1 AND ?2')
				->setParameters([1 => $matches[1], 2 => $matches[2]]);
		}
		return $qb
			->where('b.title LIKE ?1')
			->orWhere('b.altTitle LIKE ?1')
			->orWhere('b.subtitle LIKE ?1')
			->orWhere('b.author LIKE ?1')
			->orWhere('b.translator LIKE ?1')
			->orWhere('b.otherAuthors LIKE ?1')
			->orWhere('b.compiler LIKE ?1')
			->orWhere('b.editor LIKE ?1')
			->orWhere('b.publisher LIKE ?1')
			->setParameter('1', "%$query%");
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
