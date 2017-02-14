<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ShelfRepository extends EntityRepository {

	/**
	 * @return QueryBuilder
	 */
	public function forUser(User $user) {
		return $this->createQueryBuilder('s')
			->where('s.creator = ?1')->setParameter('1', $user)
			->orderBy('s.name', 'ASC');
	}

}
