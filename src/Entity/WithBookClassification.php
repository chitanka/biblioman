<?php namespace App\Entity;

use App\Collection\BookMultiFields;
use App\Entity\BookField\Isbn;
use App\Entity\BookField\IsbnClean;
use Doctrine\ORM\Mapping as ORM;

trait WithBookClassification {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	public $themes;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	public $genre;

	/**
	 * @var BookCategory
	 * @ORM\ManyToOne(targetEntity="BookCategory", fetch="EAGER")
	 */
	public $category;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $trackingCode;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $litGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30, nullable=true)
	 */
	public $uniformProductClassification;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $universalDecimalClassification;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $isbn;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $isbnClean;

	public function setThemes($themes) { $this->themes = BookMultiFields::arrayToText($themes); }
	public function setGenre($genre) { $this->genre = BookMultiFields::arrayToText($genre); }
	public function setCategory($category) { $this->category = $category; }
	public function setTrackingCode($trackingCode) { $this->trackingCode = $trackingCode; }
	public function setLitGroup($litGroup) { $this->litGroup = $litGroup; }
	public function setUniformProductClassification($uniformProductClassification) { $this->uniformProductClassification = $uniformProductClassification; }
	public function setUniversalDecimalClassification($universalDecimalClassification) { $this->universalDecimalClassification = $universalDecimalClassification; }
	public function setIsbn($isbn) {
		$this->isbn = Isbn::normalizeInput($isbn);
		$this->isbnClean = IsbnClean::normalizeInput($isbn);
	}

	public function getThemes(): array {
		return BookMultiFields::textToArray($this->themes);
	}
	public function getGenre(): array {
		return BookMultiFields::textToArray($this->genre);
	}

	protected function classificationToArray() {
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

}
