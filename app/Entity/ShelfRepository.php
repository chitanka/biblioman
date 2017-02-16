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

	public function hasBookOnShelf(Book $book, Shelf $shelf) {
		return $this->hasBookOnShelf($book, $shelf) != null;
	}

	/**
	 * @param Book $book
	 * @param Shelf $shelf
	 * @return BookOnShelf|null
	 */
	public function findBookOnShelf(Book $book, Shelf $shelf) {
		return $this->getBookOnShelfRepository()->createQueryBuilder('bs')
			->where('bs.shelf = ?1 AND bs.book = ?2')->setParameters([1 => $shelf, 2 => $book])
			->getQuery()->getOneOrNullResult();
	}

	/** @return EntityRepository */
	private function getBookOnShelfRepository() {
		return $this->_em->getRepository(BookOnShelf::class);
	}

}
