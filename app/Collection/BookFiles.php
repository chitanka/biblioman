<?php namespace App\Collection;

use App\Entity\BookFile;

class BookFiles extends Entities {

	public function onlyNew() {
		return $this->filter(function (BookFile $bookFile) {
			return $bookFile->isNew();
		});
	}

	public function notNew() {
		return $this->filter(function (BookFile $bookFile) {
			return !$bookFile->isNew();
		});
	}
}
