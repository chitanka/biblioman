<?php namespace App\Repository;

use App\Entity\User;
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

	public function fromCreatorInReverse(User $creator) {
		return $this->allInReverse()->where('r.createdBy = :creator')->setParameter(':creator', $creator->getUsername());
	}
}
