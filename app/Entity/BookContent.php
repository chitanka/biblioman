<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookContent {

	/**
	 * single collection anthology almanac
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $contentType;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $nationality;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $language = 'български';

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notesAboutOriginal;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $annotation;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notesAboutAuthor;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $marketingSnippets;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $toc;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $illustrated;

	public function getContentType() { return $this->contentType; }
	public function setContentType($contentType) { $this->contentType = $contentType; }
	public function getNationality() { return $this->nationality; }
	public function setNationality($nationality) { $this->nationality = $nationality; }
	public function getLanguage() { return $this->language; }
	public function setLanguage($language) { $this->language = $language; }
	public function getNotesAboutOriginal() { return $this->notesAboutOriginal; }
	public function setNotesAboutOriginal($notesAboutOriginal) { $this->notesAboutOriginal = $notesAboutOriginal; }
	public function getAnnotation() { return $this->annotation; }
	public function setAnnotation($annotation) { $this->annotation = Typograph::replaceAll($annotation); }
	public function getNotesAboutAuthor() { return $this->notesAboutAuthor; }
	public function setNotesAboutAuthor($notesAboutAuthor) { $this->notesAboutAuthor = Typograph::replaceAll($notesAboutAuthor); }
	public function getMarketingSnippets() { return $this->marketingSnippets; }
	public function setMarketingSnippets($marketingSnippets) { $this->marketingSnippets = Typograph::replaceAll($marketingSnippets); }
	public function getToc() { return $this->toc; }
	public function setToc($toc) { $this->toc = Typograph::replaceAll($toc); }
	public function getIllustrated() { return $this->illustrated; }
	public function setIllustrated($illustrated) { $this->illustrated = $illustrated; }

	public function toArray() {
		return [
			'contentType' => $this->contentType,
			'nationality' => $this->nationality,
			'language' => $this->language,
			'notesAboutOriginal' => $this->notesAboutOriginal,
			'annotation' => $this->annotation,
			'notesAboutAuthor' => $this->notesAboutAuthor,
			'marketingSnippets' => $this->marketingSnippets,
			'toc' => $this->toc,
			'illustrated' => $this->illustrated,
		];
	}

	public function jsonSerialize() {
		return $this->toArray();
	}
}
