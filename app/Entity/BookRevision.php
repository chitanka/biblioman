<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BookRevision {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="revisions")
	 */
	private $book;

	/**
	 * @ORM\Column(type="array")
	 */
	private $diffs;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $createdBy;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getBook() {
		return $this->book;
	}

	/**
	 * @param mixed $book
	 */
	public function setBook($book) {
		$this->book = $book;
	}

	/**
	 * @return mixed
	 */
	public function getDiffs() {
		return $this->diffs;
	}

	/**
	 * @param mixed $diffs
	 */
	public function setDiffs($diffs) {
		$this->diffs = $diffs;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @param mixed $createdBy
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param mixed $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

}
