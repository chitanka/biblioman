<?php namespace App\Entity\Query;

class YearRange {

	public $firstYear;
	public $lastYear;

	/**
	 * @param int $firstYear
	 * @param int $lastYear
	 */
	public function __construct($firstYear, $lastYear) {
		$this->firstYear = $firstYear;
		$this->lastYear = $lastYear;
	}

	public static function tryCreateFromString($string) {
		if (preg_match('/^(\d+)-(\d+)$/', $string, $matches)) {
			return new self($matches[1], $matches[2]);
		}
		return null;
	}
}
