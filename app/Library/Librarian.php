<?php namespace App\Library;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookOnShelf;
use App\Entity\Shelf;
use App\Persistence\RepositoryFinder;
use Doctrine\Common\Collections\Collection;

class Librarian {

	private $repoFinder;

	public function __construct(RepositoryFinder $repoFinder) {
		$this->repoFinder = $repoFinder;
	}

	/**
	 * @param string $textQuery
	 * @return BookSearchQuery
	 */
	public function createBookSearchQuery($textQuery, $sort = null) {
		return new BookSearchQuery($textQuery, $sort);
	}

	public function findBooksByQuery(BookSearchQuery $query) {
		return $this->repoFinder->forBook()->filterByQuery($query);
	}

	public function findBooksOnShelfByQuery(Shelf $shelf, BookSearchQuery $query) {
		if ($query->isEmpty()) {
			return $shelf->getBooksOnShelf();
		}
		return $this->repoFinder->forBook()->filterByQuery($query->shelf($shelf));
	}

	public function findBooksInCategoryByQuery(BookCategory $category, BookSearchQuery $query) {
		return $this->repoFinder->forBook()->filterByQuery($query->category($category));
	}

	/**
	 * @param array|\Traversable $result
	 * @return Book[]
	 */
	public function getBooksFromSearchResult($result) {
		$books = [];
		foreach ($result as $item) {
			$books[] = $item instanceof BookOnShelf ? $item->getBook() : $item;
		}
		return $books;
	}
}
