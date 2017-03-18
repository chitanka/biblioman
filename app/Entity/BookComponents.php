<?php namespace App\Entity;

trait BookComponents {

	use BookAuthorship { BookAuthorship::toArray as private authorshipToArray; }
	use BookBody { BookBody::toArray as private bodyToArray; }
	use BookClassification { BookClassification::toArray as private classificationToArray; }
	use BookContent { BookContent::toArray as private contentToArray; }
	use BookFiles { BookFiles::toArray as private filesToArray; }
	use BookGrouping { BookGrouping::toArray as private groupingToArray; }
	use BookMeta { BookMeta::toArray as private metaToArray; }
	use BookPrint { BookPrint::toArray as private printToArray; }
	use BookPublishing { BookPublishing::toArray as private publishingToArray; }
	use BookStaff { BookStaff::toArray as private staffToArray; }
	use BookTitling { BookTitling::toArray as private titlingToArray; }

	public function toArray() {
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
