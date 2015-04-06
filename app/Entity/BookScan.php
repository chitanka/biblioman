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
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="scans")
	 */
	private $book;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @Vich\UploadableField(mapping="book_name", fileNameProperty="name")
	 * @var File
	 */
	private $file;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	public function getId() {
		return $this->id;
	}

	public function getBook() {
		return $this->book;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setBook($book) {
		$this->book = $book;
		return $this;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
	public function setFile(File $file = null) {
		$this->file = $file;

		if ($file && $file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->setUpdatedAt(new \DateTime());
		}
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
		return $this;
	}

	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
		return $this;
	}

}
