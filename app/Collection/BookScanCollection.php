<?php namespace App\Collection;

use App\Entity\BookScan;

class BookScanCollection extends BookFileCollection {

	public function sortByTitle() {
		return self::sortCollectionByTitle($this);
	}

	/**
	 * @param BookScan[]|BookScanCollection $collection
	 * @return static
	 */
	public static function sortCollectionByTitle($collection) {
		$sortedScans = [];
		foreach ($collection as $scan) {
			$key = (int) $scan->getTitle();
			if (isset($sortedScans[$key])) {
				$sortedScans[] = $scan;
			} else {
				$sortedScans[$key] = $scan;
			}
		}
		ksort($sortedScans);
		$sortedScans = array_values($sortedScans);
		return new static($sortedScans);
	}
}
