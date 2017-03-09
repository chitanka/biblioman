<?php namespace App\Editing;

use App\Entity\Book;
use Doctrine\Common\Collections\Collection;

class Editor {

	public function computeBookDifferences(Book $book1, Book $book2) {
		$excludedFields = ['updatedAt', 'nbScans'];
		$fields1 = array_diff_key($book1->toArray(), array_flip($excludedFields));
		$diffs = $this->computeArrayDifferences($fields1, $book2->toArray());
		return $diffs;
	}

	public function computeArrayDifferences($fields1, $fields2) {
		$diffs = [];
		foreach ($fields1 as $field => $value) {
			if ($value instanceof Collection || is_array($value)) {
				if ($diff = $this->computeArrayDifferences($value, $fields2[$field])) {
					$diffs[$field] = $diff;
				}
			}
			if ($value != $fields2[$field]) {
				$diffs[$field] = [(string) $value, (string) $fields2[$field]];
			}
		}
		return $diffs;
	}
}
