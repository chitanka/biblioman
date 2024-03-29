<?php namespace App\Repository;

use App\Collection\Shelves;
use App\Entity\Book;
use App\Entity\BookOnShelf;
use App\Entity\Shelf;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ShelfRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, Shelf::class);
	}

	/**
	 * @param string|null $group
	 * @return QueryBuilder
	 */
	public function isPublic($group = null) {
		$qb = $this->createQueryBuilder('s')
			->where('s.isPublic = ?1')->setParameter('1', true)
			->join('s.creator', 'c')
			->orderBy('s.name');
		$this->addGroupFilterToQueryBuilder($qb, $group);
		return $qb;
	}

	/**
	 * @param User $user
	 * @param string|null $group
	 * @return QueryBuilder
	 */
	public function forUser(User $user, $group = null) {
		$qb = $this->createQueryBuilder('s')
			->where('s.creator = ?1')->setParameter('1', $user)
			->orderBy('s.group')->addOrderBy('s.name');
		$this->addGroupFilterToQueryBuilder($qb, $group);
		return $qb;
	}

	private function addGroupFilterToQueryBuilder(QueryBuilder $qb, $group) {
		if ($group !== null) {
			$qb->andWhere('s.group = :group')->setParameter('group', $group);
		}
		return $qb;
	}

	public function findForUser(User $user) {
		return new Shelves($this->forUser($user)->getQuery()->getResult());
	}

	public function createShelves(User $user, array $definitions) {
		$shelves = new Shelves();
		foreach ($definitions as $definition) {
			$shelf = new Shelf($user, $definition['name']);
			$shelf->setDescription($definition['description']);
			$shelf->setIcon($definition['icon']);
			$shelf->setGroup($definition['group']);
			if (!empty($definition['important'])) {
				$shelf->setIsImportant(true);
			}
			$shelves->add($shelf);
		}
		return $shelves;
	}

	/**
	 * @param array|\Traversable|Book[] $books
	 */
	public function loadShelfAssociationForBooks($books) {
		$booksById = []; /* @var $booksById Book[] */
		$shelvesByBookId = [];
		foreach ($books as $book) {
			$booksById[$book->getId()] = $book;
			$shelvesByBookId[$book->getId()] = new ArrayCollection();
		}
		$booksOnShelf = $this->getBookOnShelfRepository()->createQueryBuilder('bs')
			->where('bs.book IN (:ids)')->setParameter('ids', array_keys($booksById))
			->getQuery()->getResult(); /* @var $booksOnShelf BookOnShelf[] */
		foreach ($booksOnShelf as $bs) {
			$shelvesByBookId[$bs->getBook()->getId()][] = $bs->getShelf();
		}
		foreach ($shelvesByBookId as $bookId => $shelvesForBook) {
			$booksById[$bookId]->setShelves($shelvesForBook);
		}
	}

	public function hasBookOnShelf(Book $book, Shelf $shelf) {
		return $this->findBookOnShelf($book, $shelf) != null;
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
