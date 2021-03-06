<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookContent {

	/**
	 * single collection anthology almanac
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $contentType;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $nationality;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $language = 'български';

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $notesAboutOriginal;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $annotation;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $notesAboutAuthor;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $marketingSnippets;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $toc;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	public $illustrated;

	public function setContentType($contentType) { $this->contentType = $contentType; }
	public function setNationality($nationality) { $this->nationality = $nationality; }
	public function setLanguage($language) { $this->language = $language; }
	public function setNotesAboutOriginal($notesAboutOriginal) { $this->notesAboutOriginal = $notesAboutOriginal; }
	public function setAnnotation($annotation) { $this->annotation = Typograph::replaceAll($annotation); }
	public function setNotesAboutAuthor($notesAboutAuthor) { $this->notesAboutAuthor = Typograph::replaceAll($notesAboutAuthor); }
	public function setMarketingSnippets($marketingSnippets) { $this->marketingSnippets = Typograph::replaceAll($marketingSnippets); }
	public function setToc($toc) { $this->toc = Typograph::replaceAll($toc); }
	public function setIllustrated($illustrated) { $this->illustrated = $illustrated; }

	protected function contentToArray() {
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

}
