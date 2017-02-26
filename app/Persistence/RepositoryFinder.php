<?php namespace App\Persistence;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Repository\BookCategoryRepository;
use App\Entity\Shelf;
use App\Entity\Repository\BookRepository;
use App\Entity\Repository\ShelfRepository;

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

	protected function getRepository($entityName) {
		return $this->manager->getRepository($entityName);
	}
}
