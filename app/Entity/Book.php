<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Book {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $author;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $translator;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $editor;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publisherEditor;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $artistEditor;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $technicalEditor;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $artist;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $corrector;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $annotation;

	/**
	 * single collection anthology almanac
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $contentType;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publisher;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $classifications;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $sequence;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $sequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $infoSources;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $works;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $pubCity;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $pubDate;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $nationality;

	/**
	 * Поредност на изданието
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $edition;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $print;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $typeSettingIn;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $printSigned;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $printOut;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $printerSheets;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $publisherSheets;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $provisionPublisherSheets;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $format;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherCode;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherOrder;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $pageCount;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $price;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $binding;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $language;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $illustrated;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $notes;

	/**
	 * cover, back cover, info pages
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $scans;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $verified;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $themes;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $genre;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $category;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $revisions;

//	/**
//	 * @ORM\OneToMany(targetEntity="BookItem", mappedBy="book")
//	 * @ORM\OrderBy({"position" = "ASC"})
//	 */
//	private $items;

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function getTranslator() {
		return $this->translator;
	}

	public function getEditor() {
		return $this->editor;
	}

	public function getPublisherEditor() {
		return $this->publisherEditor;
	}

	public function getArtistEditor() {
		return $this->artistEditor;
	}

	public function getTechnicalEditor() {
		return $this->technicalEditor;
	}

	public function getArtist() {
		return $this->artist;
	}

	public function getCorrector() {
		return $this->corrector;
	}

	public function getAnnotation() {
		return $this->annotation;
	}

	public function getContentType() {
		return $this->contentType;
	}

	public function getPublisher() {
		return $this->publisher;
	}

	public function getClassifications() {
		return $this->classifications;
	}

	public function getSequence() {
		return $this->sequence;
	}

	public function getSequenceNr() {
		return $this->sequenceNr;
	}

	public function getInfoSources() {
		return $this->infoSources;
	}

	public function getWorks() {
		return $this->works;
	}

	public function getPubCity() {
		return $this->pubCity;
	}

	public function getPubDate() {
		return $this->pubDate;
	}

	public function getNationality() {
		return $this->nationality;
	}

	public function getEdition() {
		return $this->edition;
	}

	public function getPrint() {
		return $this->print;
	}

	public function getPrinterSheets() {
		return $this->printerSheets;
	}

	public function getFormat() {
		return $this->format;
	}

	public function getPageCount() {
		return $this->pageCount;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getBinding() {
		return $this->binding;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function getIllustrated() {
		return $this->illustrated;
	}

	public function getNotes() {
		return $this->notes;
	}

	public function getScans() {
		return $this->scans;
	}

	public function getVerified() {
		return $this->verified;
	}

	public function getThemes() {
		return $this->themes;
	}

	public function getGenre() {
		return $this->genre;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getRevisions() {
		return $this->revisions;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}

	public function setTranslator($translator) {
		$this->translator = $translator;
		return $this;
	}

	public function setEditor($editor) {
		$this->editor = $editor;
		return $this;
	}

	public function setPublisherEditor($publisherEditor) {
		$this->publisherEditor = $publisherEditor;
		return $this;
	}

	public function setArtistEditor($artistEditor) {
		$this->artistEditor = $artistEditor;
		return $this;
	}

	public function setTechnicalEditor($technicalEditor) {
		$this->technicalEditor = $technicalEditor;
		return $this;
	}

	public function setArtist($artist) {
		$this->artist = $artist;
		return $this;
	}

	public function setCorrector($corrector) {
		$this->corrector = $corrector;
		return $this;
	}

	public function setAnnotation($annotation) {
		$this->annotation = $annotation;
		return $this;
	}

	public function setContentType($contentType) {
		$this->contentType = $contentType;
		return $this;
	}

	public function setPublisher($publisher) {
		$this->publisher = $publisher;
		return $this;
	}

	public function setClassifications($classifications) {
		$this->classifications = $classifications;
		return $this;
	}

	public function setSequence($sequence) {
		$this->sequence = $sequence;
		return $this;
	}

	public function setSequenceNr($sequenceNr) {
		$this->sequenceNr = $sequenceNr;
		return $this;
	}

	public function setInfoSources($infoSources) {
		$this->infoSources = $infoSources;
		return $this;
	}

	public function setWorks($works) {
		$this->works = $works;
		return $this;
	}

	public function setPubCity($pubCity) {
		$this->pubCity = $pubCity;
		return $this;
	}

	public function setPubDate($pubDate) {
		$this->pubDate = $pubDate;
		return $this;
	}

	public function setNationality($nationality) {
		$this->nationality = $nationality;
		return $this;
	}

	public function setEdition($edition) {
		$this->edition = $edition;
		return $this;
	}

	public function setPrint($print) {
		$this->print = $print;
		return $this;
	}

	public function setPrinterSheets($printerSheets) {
		$this->printerSheets = $printerSheets;
		return $this;
	}

	public function setFormat($format) {
		$this->format = $format;
		return $this;
	}

	public function setPageCount($pageCount) {
		$this->pageCount = $pageCount;
		return $this;
	}

	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	public function setBinding($binding) {
		$this->binding = $binding;
		return $this;
	}

	public function setLanguage($language) {
		$this->language = $language;
		return $this;
	}

	public function setIllustrated($illustrated) {
		$this->illustrated = $illustrated;
		return $this;
	}

	public function setNotes($notes) {
		$this->notes = $notes;
		return $this;
	}

	public function setScans($scans) {
		$this->scans = $scans;
		return $this;
	}

	public function setVerified($verified) {
		$this->verified = $verified;
		return $this;
	}

	public function setThemes($themes) {
		$this->themes = $themes;
		return $this;
	}

	public function setGenre($genre) {
		$this->genre = $genre;
		return $this;
	}

	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}

	public function setRevisions($revisions) {
		$this->revisions = $revisions;
		return $this;
	}

	public function getTypeSettingIn() {
		return $this->typeSettingIn;
	}

	public function getPrintSigned() {
		return $this->printSigned;
	}

	public function getPrintOut() {
		return $this->printOut;
	}

	public function getPublisherSheets() {
		return $this->publisherSheets;
	}

	public function setTypeSettingIn($typeSettingIn) {
		$this->typeSettingIn = $typeSettingIn;
		return $this;
	}

	public function setPrintSigned($printSigned) {
		$this->printSigned = $printSigned;
		return $this;
	}

	public function setPrintOut($printOut) {
		$this->printOut = $printOut;
		return $this;
	}

	public function setPublisherSheets($publisherSheets) {
		$this->publisherSheets = $publisherSheets;
		return $this;
	}

	public function getProvisionPublisherSheets() {
		return $this->provisionPublisherSheets;
	}

	public function getPublisherCode() {
		return $this->publisherCode;
	}

	public function getPublisherOrder() {
		return $this->publisherOrder;
	}

	public function setProvisionPublisherSheets($provisionPublisherSheets) {
		$this->provisionPublisherSheets = $provisionPublisherSheets;
		return $this;
	}

	public function setPublisherCode($publisherCode) {
		$this->publisherCode = $publisherCode;
		return $this;
	}

	public function setPublisherOrder($publisherOrder) {
		$this->publisherOrder = $publisherOrder;
		return $this;
	}

	public function getCover() {
		return null;
	}

	public function getBackCover() {
		return null;
	}
}
