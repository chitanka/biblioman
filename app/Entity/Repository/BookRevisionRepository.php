<?php namespace App\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRevisionRepository extends EntityRepository {

	/**
	 * @return QueryBuilder
	 */
	public function allInReverse() {
		return $this->createQueryBuilder('r')
			->orderBy('r.createdAt', 'desc');
	}
}
