<?php namespace App\Library;

use App\Entity\Book;
use App\Php\ArrayHero;
use Pagerfanta\Pagerfanta;

class BookExport {

	const MAX_ROWS = 10000;

	/** @var Book[] */
	private $books;

	/**
	 * @param Book[] $books
	 */
	public function __construct($books) {
		$this->books = $books;
	}

	public static function fromPager(Pagerfanta $pager) {
		$pager->setMaxPerPage(static::MAX_ROWS);
		$self = new static($pager->getIterator());
		return $self;
	}

	public function toArray(array $fieldsToExport = null) {
		$data = [];
		$keysForIntersect = $fieldsToExport ? array_flip($fieldsToExport) : null;
		foreach ($this->books as $book) {
			$values = $this->bookToArray($book);
			$bookData = $keysForIntersect ? array_intersect_key($values, $keysForIntersect) : $values;
			$data[] = ArrayHero::scalarizeArray($bookData);
		}
		return $data;
	}

	/**
	 * @param Book $book
	 * @return array
	 */
	private function bookToArray($book) {
		$values = $book->toArray();
		// check if this is some book wrapper, e.g. BookOnShelf
		if (isset($values['book']) && $values['book'] instanceof Book) {
			$values = $values['book']->toArray();
		}
		return $values;
	}
}
