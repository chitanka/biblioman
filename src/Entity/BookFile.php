<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\MappedSuperclass
 * @Vich\Uploadable
 */
class BookFile extends Entity {

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
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $size;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $width;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $height;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 */
	protected $createdBy;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $createdByUser;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $updatedBy;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $updatedByUser;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updatedAt;

	public function isNew() {
		return empty($this->id);
	}

	public function toArray() {
		return parent::toArray() + [
			'title' => $this->title,
			'name' => $this->name,
			'internalFormat' => $this->internalFormat,
			'hash' => $this->hash,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->createdAt,
			'updatedBy' => $this->updatedBy,
			'updatedAt' => $this->updatedAt,
		];
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
	public function getSize() { return $this->size; }

	public function getDimensions() {
		if ($this->width && $this->height) {
			return $this->width.'×'.$this->height;
		}
		return null;
	}

	public function getFile() { return $this->file; }
	/** @param File|UploadedFile $file */
	public function setFile(File $file = null) {
		$this->file = $file;
		if ($file) {
			if ($file instanceof UploadedFile) {
				$this->fillFromUploadedFile($file);
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
	public function setCreatedByUser(User $creator) {
		$this->createdByUser = $creator;
		$this->createdBy = $creator->getUsername();
		$this->createdAt = new \DateTime();
	}

	public function getUpdatedBy() { return $this->updatedBy; }
	public function setUpdatedByUser(User $creator) {
		$this->updatedByUser = $creator;
		$this->updatedBy = $creator->getUsername();
		$this->updatedAt = new \DateTime();
	}

	public function getCreatedAt() { return $this->createdAt; }
	public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }
	public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }

	protected function fillFromUploadedFile(UploadedFile $file) {
		$this->internalFormat = $file->guessExtension();
		$this->size = $file->getSize();
		if ($this->size > 0 && $imageInfo = getimagesize($file->getRealPath())) {
			$this->width = $imageInfo[0];
			$this->height = $imageInfo[1];
		}
		$this->updatedAt = new \DateTime();
	}
}
