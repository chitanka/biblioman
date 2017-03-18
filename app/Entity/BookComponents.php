<?php namespace App\Entity;

trait BookComponents {

	use BookAuthorship;
	use BookBody;
	use BookClassification;
	use BookContent;
	use BookFiles;
	use BookGrouping;
	use BookMeta;
	use BookPrint;
	use BookPublishing;
	use BookStaff;
	use BookTitling;

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
