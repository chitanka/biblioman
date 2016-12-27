<?php namespace App\Entity;

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
