<?php namespace App\Repository;

use App\Entity\Label;

class LabelRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, Label::class);
	}

	public function findAllGenres(): array {
		return $this->findAllForGroup(Label::GROUP_GENRE);
	}

	public function findAllCharacteristics(): array {
		return $this->findAllForGroup(Label::GROUP_CHARACTERISTIC);
	}

	private function findAllForGroup(string $group): array {
		return array_column($this->createQueryBuilder('l')
			->select('l.name')
			->where('l.group = ?1')->setParameter('1', $group)
			->orderBy('l.name')
			->getQuery()->getScalarResult(), 'name');
	}
}
