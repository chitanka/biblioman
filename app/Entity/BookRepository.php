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
}
