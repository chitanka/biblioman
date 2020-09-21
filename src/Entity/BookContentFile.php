<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class BookContentFile extends BookFile {

	/** @ORM\ManyToOne(targetEntity="Book", inversedBy="contentFiles") */
	protected $book;

	/**
	 * @var UploadedFile
	 * @Vich\UploadableField(mapping="fullcontent", fileNameProperty="name")
	 */
	protected $file;

	public function __toString() {
		return $this->getTitle() ?: $this->getName();
	}

	/** @ORM\PrePersist */
	public function onPreInsert() {
		if (empty($this->getTitle())) {
			$this->setTitle($this->file->getClientOriginalName());
		}
	}

}
