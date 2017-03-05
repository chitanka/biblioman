<?php namespace App\Library;

use App\Entity\BookCategory;
use App\Entity\Shelf;

class BookSearchCriteria {

	const FIELD_SEARCH_SEPARATOR = ':';
	const SORT_FIELD_SEPARATOR = ',';
	const SORT_ORDER_SEPARATOR = '-';

	private static $linkedSortableFields = [
		'sequence' => ['sequenceNr-asc'],
		'subsequence' => ['subsequenceNr-asc'],
		'series' => ['seriesNr-asc'],
	];

	public $field;
	public $term;
	public $normalized;
	public $raw;
	public $sort = [];

	/** @var Shelf[] */
	public $shelves = [];
	/** @var BookCategory[] */
	public $categories = [];

	public function __construct($textQuery, $sort = null) {
		$this->raw = trim($textQuery);
		if (strpos($this->raw, self::FIELD_SEARCH_SEPARATOR) !== false) {
			list($this->field, $this->term) = array_map('trim', explode(self::FIELD_SEARCH_SEPARATOR, $this->raw));
		} else {
			$this->term = $this->raw;
		}
		$this->normalized = BookField::normalizedFieldValue($this->field, $this->term);
		$this->sortBy($sort);
	}

	public function inShelf($shelf) {
		return $this->inShelves($shelf);
	}
	public function inShelves($shelves) {
		$this->shelves = array_merge($this->shelves, $this->asArray($shelves));
		return $this;
	}

	public function inCategory($category) {
		return $this->inCategories($category);
	}
	public function inCategories($categories) {
		$this->categories = array_merge($this->categories, $this->asArray($categories));
		return $this;
	}

	public function sortBy($sortQuery = null) {
		if (empty($sortQuery)) {
			$sortQuery = $this->getDefaultSort();
		}
		foreach (explode(self::SORT_FIELD_SEPARATOR, $sortQuery) as $orderBy) {
			$orderBy = ltrim($orderBy);
			if (strpos($orderBy, self::SORT_ORDER_SEPARATOR) === false) {
				$field = $orderBy;
				$order = 'asc';
			} else {
				list($field, $order) = explode(self::SORT_ORDER_SEPARATOR, ltrim($orderBy));
			}
			$this->sort[$field] = $order;
		}
		return $this;
	}

	public function isEmpty() {
		return empty($this->term);
	}

	private function getDefaultSort() {
		if (isset(self::$linkedSortableFields[$this->field])) {
			return implode(self::SORT_FIELD_SEPARATOR, self::$linkedSortableFields[$this->field]);
		}
		return 'title';
	}

	private function asArray($thing) {
		return is_array($thing) ? $thing : [$thing];
	}
}
