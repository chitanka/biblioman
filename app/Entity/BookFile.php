<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\MappedSuperclass
 * @Vich\Uploadable
 */
class BookFile extends Entity implements \JsonSerializable {

	/**
	 * @var Book
	 */
	protected $book;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30)
	 */
	protected $name;

	/**
	 * @var File
	 */
	protected $file;

	/**
	 * Internal storage format
	 * @var string
	 * @ORM\Column(type="string", length=4, nullable=true)
	 */
	protected $internalFormat;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=40)
	 */
	protected $hash;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 */
	protected $createdBy;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	public function isNew() {
		return empty($this->id);
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'name' => $this->name,
			'internalFormat' => $this->internalFormat,
			'hash' => $this->hash,
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

	public function getBook() { return $this->book; }
	public function setBook(Book $book) { $this->book = $book; }
	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = $title; }
	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
	public function getInternalFormat() { return $this->internalFormat; }
	public function setInternalFormat($internalFormat) { $this->internalFormat = $internalFormat; }
	public function getHash() { return $this->hash; }
	public function setHash($hash) { $this->hash = $hash; }

	public function getFile() { return $this->file; }
	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFile(File $file = null) {
		$this->file = $file;
		if ($file) {
			if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
				$this->setInternalFormat($file->guessExtension());
				$this->updatedAt = new \DateTime();
			}
			$this->setHashFromPath($file->getRealPath());
		}
	}

	public function setHashFromPath($path) {
		if ($path) {
			$this->setHash(md5_file($path));
		}
	}

	public function getCreatedBy() { return $this->createdBy; }
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		$this->createdAt = new \DateTime();
	}

	public function getCreatedAt() { return $this->createdAt; }
	public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }
	public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
}
