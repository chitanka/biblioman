<?php namespace App\Persistence;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Repository\BookRepository;
use App\Repository\ShelfRepository;
use Entity\Category;
use Entity\Repository\CategoryRepository;

class RepositoryFinder {

	protected $manager;

	public function __construct(Manager $manager) {
		$this->manager = $manager;
	}

	/** @return BookRepository */
	public function forBook() {
		return $this->getRepository(Book::class);
	}

	/** @return CategoryRepository */
	public function forCategory() {
		return $this->getRepository(Category::class);
	}

	/** @return ShelfRepository */
	public function forShelf() {
		return $this->getRepository(Shelf::class);
	}

	protected function getRepository($entityName) {
		return $this->manager->getRepository($entityName);
	}
}
