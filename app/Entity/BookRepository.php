<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;

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
}
