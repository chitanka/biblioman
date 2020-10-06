<?php namespace App\Repository;

use App\Entity\BookMultiField;

class BookMultiFieldRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Common\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, BookMultiField::class);
	}

	public function findAllGenres() {
		return $this->findAllForField('genre');
	}

	public function findAllThemes() {
		return $this->findAllForField('themes');
	}

	protected function findAllForField(string $field) {
		return array_column($this->createQueryBuilder('f')
			->select('f.value')
			->where("f.field = '$field'")
			->distinct()
			->orderBy('f.value')
			->getQuery()->getScalarResult(), 'value');
	}
}
