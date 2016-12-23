<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BookLink {

	public static $categories = [
		'library',
		'publisher',
		'blog',
		'forum',
		'bookstore',
	];

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="links")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $book;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=500)
	 */
	private $url;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=10)
	 */
	private $category;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $author;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getBook() {
		return $this->book;
	}

	public function setBook(Book $book) {
		$this->book = $book;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setAuthor($author) {
		$this->author = $author;
	}

}
