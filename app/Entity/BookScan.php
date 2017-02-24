<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookScan extends Entity implements \JsonSerializable {

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="scans")
	 */
	private $book;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30)
	 */
	private $name;

	/**
	 * Internal storage format
	 * @var string
	 * @ORM\Column(type="string", length=4, nullable=true)
	 */
	private $internalFormat;

	/**
	 * @var File
	 * @Vich\UploadableField(mapping="scan", fileNameProperty="name")
	 */
	private $file;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 */
	private $createdBy;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	public function __toString() {
		$title = $this->getTitle();
		if (empty($title)) {
			return '';
		}
		if (is_numeric($title)) {
			return 'Страница '.$title;
		}
		if (preg_match('/^\d+[-и ,]+\d+$/u', $title)) {
			return 'Страници '.$title;
		}
		return $title;
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'internalFormat' => $this->internalFormat,
			'title' => $this->title,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
		];
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->toArray();
	}

	public function getBook() {
		return $this->book;
	}

	public function setBook($book) {
		$this->book = $book;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setName($name) {
		// TODO make it smarter
		$this->name = str_replace('.tif', '.png', $name);
	}

	public function getName() {
		return $this->name;
	}

	public function getInternalFormat() {
		return $this->internalFormat;
	}

	public function setInternalFormat($internalFormat) {
		$this->internalFormat = $internalFormat;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
	public function setFile(File $file = null) {
		$this->file = $file;
		if ($file && $file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->setInternalFormat($file->guessExtension());
			$this->setUpdatedAt(new \DateTime());
		}
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->file;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	public function getCreatedBy() {
		return $this->createdBy;
	}

	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		$this->setCreatedAt(new \DateTime());
	}

	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
	}

}
