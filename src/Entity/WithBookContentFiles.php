<?php namespace App\Entity;

use App\Collection\BookContentFiles;
use Doctrine\ORM\Mapping as ORM;

trait WithBookContentFiles {

	/**
	 * @var BookContentFile[]|BookContentFiles
	 * @ORM\OneToMany(targetEntity="BookContentFile", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	private $contentFiles;

	/**
	 * Number of uploaded content files for the book
	 * @ORM\Column(type="smallint")
	 */
	public $nbContentFiles;

	/** @return BookContentFile[]|BookContentFiles */
	public function getContentFiles() {
		// @fixme fill the Book reference when setting $contentFiles
		foreach ($this->contentFiles as $contentFile) {
			if ($contentFile->getBook() === null) {
				$contentFile->setBook($this);
			}
		}
		return new BookContentFiles($this->contentFiles);
	}

	/** @param BookContentFile[] $contentFiles */
	public function setContentFiles($contentFiles) {
		$this->contentFiles = $contentFiles;
		$this->updateNbContentFiles();
	}

	public function addContentFile(BookContentFile $contentFile) {
		if (!empty($contentFile->getFile())) {
			$contentFile->setBook($this);
			$this->contentFiles[] = $contentFile;
			$this->updateNbContentFiles();
		}
	}

	public function removeContentFile(BookContentFile $contentFile) {
		$this->contentFiles->removeElement($contentFile);
		$this->updateNbContentFiles();
	}

	protected function updateNbContentFiles() {
		$this->nbContentFiles = count($this->contentFiles);
	}

}
