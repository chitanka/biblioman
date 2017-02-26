<?php namespace App\Entity;

class BookSearchQuery {

	public $field;
	public $term;
	public $normalized;
	public $raw;

	/** @var Shelf */
	public $shelf;
	/** @var BookCategory */
	public $category;

	public function isEmpty() {
		return empty($this->term);
	}
}
