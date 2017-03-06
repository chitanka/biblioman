<?php namespace App\Collection;

use App\Entity\BookFile;

class BookFileCollection extends EntityCollection {

	public function onlyNew() {
		return $this->filter(function (BookFile $bookFile) {
			return $bookFile->isNew();
		});
	}
}
