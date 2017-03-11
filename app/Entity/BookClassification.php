<?php namespace App\Entity;

use App\Library\BookField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookClassification {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $themes;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $genre;

	/**
	 * @var BookCategory
	 * @ORM\ManyToOne(targetEntity="BookCategory", fetch="EAGER")
	 */
	private $category;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $trackingCode;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $litGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30, nullable=true)
	 */
	private $uniformProductClassification;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30, nullable=true)
	 */
	private $universalDecimalClassification;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $isbn;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $isbnClean;

	public function getThemes() { return $this->themes; }
	public function setThemes($themes) { $this->themes = $themes; }
	public function getGenre() { return $this->genre; }
	public function setGenre($genre) { $this->genre = $genre; }
	public function getCategory() { return $this->category; }
	public function setCategory($category) { $this->category = $category; }
	public function getTrackingCode() { return $this->trackingCode; }
	public function setTrackingCode($trackingCode) { $this->trackingCode = $trackingCode; }
	public function getLitGroup() { return $this->litGroup; }
	public function setLitGroup($litGroup) { $this->litGroup = $litGroup; }
	public function getUniformProductClassification() { return $this->uniformProductClassification; }
	public function setUniformProductClassification($uniformProductClassification) { $this->uniformProductClassification = $uniformProductClassification; }
	public function getUniversalDecimalClassification() { return $this->universalDecimalClassification; }
	public function setUniversalDecimalClassification($universalDecimalClassification) { $this->universalDecimalClassification = $universalDecimalClassification; }
	public function getIsbn() { return $this->isbn; }
	public function setIsbn($isbn) { $this->isbn = BookField::normalizeIsbn($isbn); $this->setIsbnClean(BookField::normalizeSearchableIsbn($this->isbn)); }
	public function getIsbnClean() { return $this->isbnClean; }
	public function setIsbnClean($isbnClean) { $this->isbnClean = $isbnClean; }

	public function toArray() {
		return [
			'themes' => $this->themes,
			'genre' => $this->genre,
			'category' => $this->category,
			'trackingCode' => $this->trackingCode,
			'litGroup' => $this->litGroup,
			'uniformProductClassification' => $this->uniformProductClassification,
			'universalDecimalClassification' => $this->universalDecimalClassification,
			'isbn' => $this->isbn,
		];
	}

	public function jsonSerialize() {
		return $this->toArray();
	}
}
