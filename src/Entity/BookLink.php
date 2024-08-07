<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BookLink extends Entity {

	private const COBISS_URL = 'https://plus.cobiss.net/cobiss/bg/bg/bib/{ID}#full';

	public static $categories = [
		'library',
		'publisher',
		'bibliography',
		'encyclopedia',
		'blog',
		'personal',
		'forum',
		'bookstore',
		'other',
	];

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
	 * @ORM\Column(type="string", length=15)
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

	public static function createFromCobissId(int $cobissId): self {
		$self = new self;
		$self->url = str_replace('{ID}', $cobissId, self::COBISS_URL);
		$self->title = 'COBISS';
		$self->category = 'bibliography';
		return $self;
	}

	public function __toString() {
		return $this->getUrl();
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

	public function toArray() {
		return [
			'book' => $this->getBook(),
			'url' => $this->url,
			'category' => $this->category,
			'title' => $this->title,
			'author' => $this->author,
		];
	}
}
