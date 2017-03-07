<?php namespace App\Collection;

use App\Entity\BookScan;

class BookScanCollection extends BookFileCollection {

	public function sortByTitle() {
		$sortedScans = [];
		foreach ($this->objects() as $scan) {
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

	/** @return BookScan[]|static */
	private function objects() { return $this; }
}
