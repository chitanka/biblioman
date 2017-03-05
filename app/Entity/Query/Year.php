<?php namespace App\Entity\Query;

class Year {

	public $year;

	/**
	 * @param int $year
	 */
	public function __construct($year) {
		$this->year = (int) $year;
	}

	public static function tryCreateFromString($string) {
		if (is_numeric($string)) {
			return new self($string);
		}
		return null;
	}
}
