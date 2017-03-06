<?php namespace App\Collection;

use Doctrine\Common\Collections\Collection;

class BookScanCollection extends BookFileCollection {

	public function sortByTitle() {
		return self::sortCollectionByTitle($this);
	}

	public static function sortCollectionByTitle(Collection $collection) {
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
