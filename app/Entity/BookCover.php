<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookCover implements \JsonSerializable {

	const TYPE_FRONT = 'front';
	const TYPE_BACK = 'back';
	const TYPE_INNER = 'inner';
	const TYPE_OTHER = 'other';

	public static $types = [
		self::TYPE_FRONT,
		self::TYPE_BACK,
		self::TYPE_INNER,
		self::TYPE_OTHER,
	];

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="covers")
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
	 * @var string
	 * @ORM\Column(type="string", length=10)
	 */
	private $type;

	/**
	 * @var File
	 * @Vich\UploadableField(mapping="cover", fileNameProperty="name")
	 */
	private $file;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=40)
	 */
	private $hash;

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
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	public function __toString() {
		return $this->getType();
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'title' => $this->title,
			'type' => $this->type,
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

	public function isNew() {
		return empty($this->id);
	}

	public function getId() { return $this->id; }
	public function getBook() { return $this->book; }
	public function setBook($book) { $this->book = $book; }
	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = $title; }
	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
	public function getType() { return $this->type; }
	public function setType($type) { $this->type = $type; }

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFile(File $file = null) {
		$this->file = $file;
		if ($file) {
			if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
				$this->setUpdatedAt(new \DateTime());
			}
			$this->setHash(md5_file($file->getRealPath()));
		}
	}
	public function getFile() { return $this->file; }

	public function getHash() { return $this->hash; }
	public function setHash($hash) { $this->hash = $hash; }
	public function getCreatedAt() { return $this->createdAt; }
	public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }

	public function getCreatedBy() { return $this->createdBy; }
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		$this->setCreatedAt(new \DateTime());
	}

	public function getUpdatedAt() { return $this->updatedAt; }
	public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
}
