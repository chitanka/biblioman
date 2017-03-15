<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait BookStaff {

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $chiefEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $managingEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $editor;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $editorialStaff;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $publisherEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $artistEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $technicalEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $consultant;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $scienceEditor;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $copyreader;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $reviewer;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $artist;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $illustrator;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $corrector;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $layout;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $coverLayout;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $libraryDesign;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $computerProcessing;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $prepress;

	public function setChiefEditor($chiefEditor) { $this->chiefEditor = $chiefEditor; }
	public function setManagingEditor($managingEditor) { $this->managingEditor = $managingEditor; }
	public function setEditor($editor) { $this->editor = $editor; }
	public function setEditorialStaff($editorialStaff) { $this->editorialStaff = $editorialStaff; }
	public function setPublisherEditor($publisherEditor) { $this->publisherEditor = $publisherEditor; }
	public function setArtistEditor($artistEditor) { $this->artistEditor = $artistEditor; }
	public function setTechnicalEditor($technicalEditor) { $this->technicalEditor = $technicalEditor; }
	public function setConsultant($consultant) { $this->consultant = $consultant; }
	public function setScienceEditor($scienceEditor) { $this->scienceEditor = $scienceEditor; }
	public function setCopyreader($copyreader) { $this->copyreader = $copyreader; }
	public function setReviewer($reviewer) { $this->reviewer = $reviewer; }
	public function setArtist($artist) { $this->artist = $artist; }
	public function setIllustrator($illustrator) { $this->illustrator = $illustrator; }
	public function setCorrector($corrector) { $this->corrector = $corrector; }
	public function setLayout($layout) { $this->layout = $layout; }
	public function setCoverLayout($coverLayout) { $this->coverLayout = Typograph::replaceAll($coverLayout); }
	public function setLibraryDesign($libraryDesign) { $this->libraryDesign = Typograph::replaceAll($libraryDesign); }
	public function setComputerProcessing($computerProcessing) { $this->computerProcessing = Typograph::replaceAll($computerProcessing); }
	public function setPrepress($prepress) { $this->prepress = Typograph::replaceAll($prepress); }

	public function toArray() {
		return [
			'chiefEditor' => $this->chiefEditor,
			'managingEditor' => $this->managingEditor,
			'editor' => $this->editor,
			'editorialStaff' => $this->editorialStaff,
			'publisherEditor' => $this->publisherEditor,
			'artistEditor' => $this->artistEditor,
			'technicalEditor' => $this->technicalEditor,
			'consultant' => $this->consultant,
			'scienceEditor' => $this->scienceEditor,
			'copyreader' => $this->copyreader,
			'reviewer' => $this->reviewer,
			'artist' => $this->artist,
			'illustrator' => $this->illustrator,
			'corrector' => $this->corrector,
			'layout' => $this->layout,
			'coverLayout' => $this->coverLayout,
			'libraryDesign' => $this->libraryDesign,
			'computerProcessing' => $this->computerProcessing,
			'prepress' => $this->prepress,
		];
	}

}
