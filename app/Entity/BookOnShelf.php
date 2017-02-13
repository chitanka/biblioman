<?php namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BookOnShelf {

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book")
	 */
	private $book;

	/**
	 * @var Shelf
	 * @ORM\ManyToOne(targetEntity="Shelf")
	 */
	private $shelf;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $position;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	public function __construct(Book $book, Shelf $shelf) {
		$this->setBook($book);
		$this->setShelf($shelf);
		$this->setPosition(0);
	}

	public function getId() { return $this->id; }
	public function getBook() { return $this->book; }
	public function setBook($book) { $this->book = $book; }
	public function getShelf() { return $this->shelf; }
	public function setShelf($shelf) { $this->shelf = $shelf; }
	public function getPosition() { return $this->position; }
	public function setPosition($position) { $this->position = $position; }
	public function getCreatedAt() { return $this->createdAt; }
}
