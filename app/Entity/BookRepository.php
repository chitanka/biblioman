<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends EntityRepository {

	public function findRecent($maxResults = 5) {
		return $this->createQueryBuilder('b')
			->orderBy('b.createdAt', 'DESC')
			->setMaxResults($maxResults)
			->getQuery()
			->getResult();
	}

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
		if (is_numeric($query)) {
			$qb
				->where('b.pubDate = ?1')
				->setParameter('1', $query);
		} else if (preg_match('/(\d+)-(\d+)/', $query, $matches)) {
			$qb
				->where('b.pubDate BETWEEN ?1 AND ?2')
				->setParameters([1 => $matches[1], 2 => $matches[2]]);
		} else {
			$qb
				->where('b.title LIKE ?1')
				->orWhere('b.subtitle LIKE ?1')
				->orWhere('b.author LIKE ?1')
				->orWhere('b.translator LIKE ?1')
				->orWhere('b.compiler LIKE ?1')
				->orWhere('b.editor LIKE ?1')
				->orWhere('b.publisher LIKE ?1')
				->setParameter('1', "%$query%");
		}
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
}
