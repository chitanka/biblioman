<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRevisionRepository")
 * @ORM\Table
 */
class BookRevision extends Entity {

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
	 * @var User
	 */
	private $createdByUser;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

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

	public function getCreatedByUser(): User {
		return $this->createdByUser ?? $this->createdByUser = new User($this->getCreatedBy(), '');
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

	public function toArray() {
		return [
			'book' => $this->getBook(),
			'diffs' => $this->diffs,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->createdAt,
		];
	}
}
