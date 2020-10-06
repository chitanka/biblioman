<?php namespace App\Repository;

use App\Entity\BookRevision;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class BookRevisionRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Common\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, BookRevision::class);
	}

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
