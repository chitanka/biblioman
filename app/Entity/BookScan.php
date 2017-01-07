<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookScan {

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

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

	public function __toString() {
		$title = $this->getTitle();
		if (empty($title)) {
			return '';
		}
		if (is_numeric($title)) {
			return 'Страница '.$title;
		}
		if (preg_match('/^\d+[- ,]\d+$/', $title)) {
			return 'Страници '.$title;
		}
		return $title;
	}

	public function getId() {
		return $this->id;
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
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
	public function setFile(File $file = null) {
		$this->file = $file;
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

}
