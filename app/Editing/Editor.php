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
			$diffs[$field] = $this->computeFieldDifferences($value, $fields2[$field]);
		}
		$diffs = array_filter($diffs);
		return $diffs;
	}

	private function computeFieldDifferences($field1, $field2) {
		if ($this->isCollection($field1)) {
			return array_filter($this->computeArrayDifferences($field1, $field2));
		}
		if ($field1 != $field2) {
			return [(string) $field1, (string) $field2];
		}
		return null;
	}

	private function isCollection($var) {
		return $var instanceof Collection || is_array($var);
	}
}
