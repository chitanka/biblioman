<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends EntityRepository {

	public static $searchableFields = [
		'author',
		'title',
		'subtitle',
		'sequence',
		'translator',
		'translatedFromLanguage',
		'dateOfTranslation',
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
		'pubCity',
		'pubDate',
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
		'binding',
		'illustrated',
		'isbn10',
		'isbn13',
	];

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
	 * @return QueryBuilder
	 */
	public function filterByQuery($query) {
		$qb = $this->createQueryBuilder('b');
		if (empty($query)) {
			return $qb;
		}
		if (strpos($query, ':') !== false) {
			list($searchField, $fieldQuery) = explode(':', $query);
			$fieldQuery = trim($fieldQuery);
			$allowedFields = self::$searchableFields;
			if (in_array($searchField, $allowedFields)) {
				if ($fieldQuery[0] === '"') {
					$operator = '=';
					$fieldQuery = trim($fieldQuery, '"');
				} else {
					$operator = 'LIKE';
					$fieldQuery = "%$fieldQuery%";
				}
				return $qb->where("b.{$searchField} $operator ?1")->setParameter('1', $fieldQuery);
			}
		}
		if (is_numeric($query)) {
			return $qb
				->where('b.pubDate = ?1')
				->setParameter('1', $query);
		}
		if (preg_match('/(\d+)-(\d+)/', $query, $matches)) {
			return $qb
				->where('b.pubDate BETWEEN ?1 AND ?2')
				->setParameters([1 => $matches[1], 2 => $matches[2]]);
		}
		return $qb
			->where('b.title LIKE ?1')
			->orWhere('b.subtitle LIKE ?1')
			->orWhere('b.author LIKE ?1')
			->orWhere('b.translator LIKE ?1')
			->orWhere('b.compiler LIKE ?1')
			->orWhere('b.editor LIKE ?1')
			->orWhere('b.publisher LIKE ?1')
			->setParameter('1', "%$query%");
	}

	/**
	 * @return QueryBuilder
	 */
	public function filterIncomplete() {
		return $this->createQueryBuilder('b')
			->where('b.isIncomplete = 1')
			->orWhere('b.nbScans = 0');
	}
}
