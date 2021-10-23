<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookAuthorship {

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	public $author;

	/**
	 * @ORM\Column(type="string", length=2500, nullable=true)
	 */
	public $translator;

	/**
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	public $translatedFromLanguage;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $dateOfTranslation;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $adaptedBy;

	/**
	 * @ORM\Column(type="string", length=2500, nullable=true)
	 */
	public $otherAuthors;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $compiler;

	public function setAuthor($author) { $this->author = Typograph::replaceDash($author); }
	public function setTranslator($translator) { $this->translator = Typograph::replaceDash($translator); }
	public function setTranslatedFromLanguage($translatedFromLanguage) { $this->translatedFromLanguage = $translatedFromLanguage; }
	public function setDateOfTranslation($dateOfTranslation) { $this->dateOfTranslation = $dateOfTranslation; }
	public function setAdaptedBy($adaptedBy) { $this->adaptedBy = Typograph::replaceDash($adaptedBy); }
	public function setOtherAuthors($otherAuthors) { $this->otherAuthors = Typograph::replaceDash($otherAuthors); }
	public function setCompiler($compiler) { $this->compiler = Typograph::replaceDash($compiler); }

	public function getAuthorsAtMost($count) {
		return implode(';', array_slice(explode(';', $this->author), 0, $count));
	}

	protected function authorshipToArray() {
		return [
			'author' => $this->author,
			'translator' => $this->translator,
			'translatedFromLanguage' => $this->translatedFromLanguage,
			'dateOfTranslation' => $this->dateOfTranslation,
			'adaptedBy' => $this->adaptedBy,
			'otherAuthors' => $this->otherAuthors,
			'compiler' => $this->compiler,
		];
	}

}
