<?php namespace App\Entity;

use App\Entity\BookField\Isbn;
use App\Entity\BookField\IsbnClean;
use Doctrine\ORM\Mapping as ORM;

trait WithBookClassification {

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
	 * @ORM\Column(type="string", length=100, nullable=true)
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

	public function setThemes($themes) { $this->themes = $themes; }
	public function setGenre($genre) { $this->genre = $genre; }
	public function setCategory($category) { $this->category = $category; }
	public function setTrackingCode($trackingCode) { $this->trackingCode = $trackingCode; }
	public function setLitGroup($litGroup) { $this->litGroup = $litGroup; }
	public function setUniformProductClassification($uniformProductClassification) { $this->uniformProductClassification = $uniformProductClassification; }
	public function setUniversalDecimalClassification($universalDecimalClassification) { $this->universalDecimalClassification = $universalDecimalClassification; }
	public function setIsbn($isbn) {
		$this->isbn = Isbn::normalizeInput($isbn);
		$this->isbnClean = IsbnClean::normalizeInput($isbn);
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
