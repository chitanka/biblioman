<?php namespace App\Collection;

use App\Entity\Book;
use App\Entity\BookMultiField;

class BookMultiFields extends Entities {

	const MULTI_FIELDS = [
		'author',
		'title',
		'altTitle',
		'subtitle',
		'volumeTitle',
		'translator',
		'adaptedBy',
		'otherAuthors',
		'compiler',
		'chiefEditor',
		'managingEditor',
		'editor',
		'editorialStaff',
		'publisherEditor',
		'artistEditor',
		'technicalEditor',
		'consultant',
		'scienceEditor',
		'copyreader',
		'reviewer',
		'artist',
		'illustrator',
		'corrector',
		'layout',
		'coverLayout',
		'libraryDesign',
		'computerProcessing',
		'prepress',
		'isbnClean',
		'publisher',
		'nationality',
		'language',
		'edition',
		'genre',
		'themes',
	];
	const VALUE_SEPARATOR = ';';

	/**
	 * @param BookMultiField $multiField
	 * @return bool
	 */
	public function removeElement($multiField) {
		$result = parent::removeElement($multiField);
		if ($result) {
			$multiField->remove();
		}
		return $result;
	}

	public function updateFromBook(Book $book) {
		$newMultiFields = self::fromBook($book);
		foreach ($newMultiFields->diff($this) as $addedMultiField) {
			$this->collection->add($addedMultiField);
		}
		foreach ($this->diff($newMultiFields) as $removedMultiField) {
			$this->collection->removeElement($removedMultiField);
		}
	}

	private static function fromBook(Book $book) {
		$multiFields = [];
		foreach (self::MULTI_FIELDS as $multiFieldName) {
			$multiFieldValues = self::getMultiFieldValuesFromBook($book, $multiFieldName);
			foreach ($multiFieldValues as $multiFieldValue) {
				if ($multiFieldValue) {
					$multiField = new BookMultiField($book, $multiFieldName, $multiFieldValue);
					$multiFields[$multiField->getUniqueKey()] = $multiField;
				}
			}
		}
		return new self($multiFields);
	}

	private static function getMultiFieldValuesFromBook(Book $book, $multiField) {
		return array_map('trim', explode(self::VALUE_SEPARATOR, $book->{'get'.ucfirst($multiField)}()));
	}

}
