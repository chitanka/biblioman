<?php namespace App\Entity;

trait WithBookComponents {

	use WithBookAuthorship;
	use WithBookBody;
	use WithBookClassification;
	use WithBookContent;
	use WithBookFiles;
	use WithBookGrouping;
	use WithBookMeta;
	use WithBookPrint;
	use WithBookPublishing;
	use WithBookStaff;
	use WithBookTitling;

	protected function componentsToArray() {
		return $this->titlingToArray() +
			$this->authorshipToArray() +
			$this->bodyToArray() +
			$this->classificationToArray() +
			$this->contentToArray() +
			$this->filesToArray() +
			$this->groupingToArray() +
			$this->metaToArray() +
			$this->printToArray() +
			$this->publishingToArray() +
			$this->staffToArray();
	}

}
