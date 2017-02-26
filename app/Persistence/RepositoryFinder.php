<?php namespace App\Persistence;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Repository\BookCategoryRepository;
use App\Entity\Repository\UserRepository;
use App\Entity\Shelf;
use App\Entity\Repository\BookRepository;
use App\Entity\Repository\ShelfRepository;
use App\Entity\User;

class RepositoryFinder {

	protected $manager;

	public function __construct(Manager $manager) {
		$this->manager = $manager;
	}

	/** @return BookRepository */
	public function forBook() {
		return $this->getRepository(Book::class);
	}

	/** @return BookCategoryRepository */
	public function forBookCategory() {
		return $this->getRepository(BookCategory::class);
	}

	/** @return ShelfRepository */
	public function forShelf() {
		return $this->getRepository(Shelf::class);
	}

	/** @return UserRepository */
	public function forUser() {
		return $this->getRepository(User::class);
	}

	protected function getRepository($entityName) {
		return $this->manager->getRepository($entityName);
	}
}
