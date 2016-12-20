<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="App\Entity\BookRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Book implements \JsonSerializable {

	const LOCK_EXPIRE_TIME = 21600;

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $subtitle;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $author;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $translator;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $translatedFromLanguage;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	private $dateOfTranslation;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $compiler;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $chiefEditor;

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
	private $reviewer;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $artist;

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
	private $computerProcessing;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $prepress;

	/**
	 * single collection anthology almanac
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $contentType;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
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
	 * @ORM\Column(type="string", length=10, nullable=true)
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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $publisherAddress;

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
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $litGroup;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
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
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherNumber;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $trackingCode;

	/**
	 * @ORM\Column(type="string", length=30, nullable=true)
	 */
	private $uniformProductClassification;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $totalPrint;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $pageCount;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
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
	 * @ORM\Column(type="string", length=15, nullable=true)
	 */
	private $isbn10;

	/**
	 * @ORM\Column(type="string", length=18, nullable=true)
	 */
	private $isbn13;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notes;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notesAboutOriginal;

	/**
	 * Number of uploaded scans for the book
	 * @ORM\Column(type="smallint")
	 */
	private $nbScans;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $source;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $verified;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $annotation;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $marketingSnippets;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $toc;

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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $cover;

	/**
	 * @Vich\UploadableField(mapping="scan", fileNameProperty="cover")
	 * @var File
	 */
	private $coverFile;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $backCover;

	/**
	 * @Vich\UploadableField(mapping="scan", fileNameProperty="backCover")
	 * @var File
	 */
	private $backCoverFile;

	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan1;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan1") @var File */
	private $scan1File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan2;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan2") @var File */
	private $scan2File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan3;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan3") @var File */
	private $scan3File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan4;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan4") @var File */
	private $scan4File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan5;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan5") @var File */
	private $scan5File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan6;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan6") @var File */
	private $scan6File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan7;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan7") @var File */
	private $scan7File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan8;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan8") @var File */
	private $scan8File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan9;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan9") @var File */
	private $scan9File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan10;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan10") @var File */
	private $scan10File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan11;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan11") @var File */
	private $scan11File;
	/** @ORM\Column(type="string", length=100, nullable=true) */
	private $scan12;
	/** @Vich\UploadableField(mapping="scan", fileNameProperty="scan12") @var File */
	private $scan12File;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $chitankaId;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $createdBy;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	/**
	 * @ORM\OneToMany(targetEntity="BookRevision", mappedBy="book")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
	 */
	private $revisions;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	private $isIncomplete;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $lockedAt;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $lockedBy;

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

	public function getConsultant() {
		return $this->consultant;
	}

	public function setConsultant($consultant) {
		$this->consultant = $consultant;
		return $this;
	}

	public function getReviewer() {
		return $this->reviewer;
	}

	public function setReviewer($reviewer) {
		$this->reviewer = $reviewer;
		return $this;
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

	public function getPublisherAddress() {
		return $this->publisherAddress;
	}

	public function setPublisherAddress($publisherAddress) {
		$this->publisherAddress = $publisherAddress;
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

	public function getNbScans() {
		return $this->nbScans;
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
	public function getSubtitle() {
		return $this->subtitle;
	}

	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
		return $this;
	}

	public function getCompiler() {
		return $this->compiler;
	}

	public function getLayout() {
		return $this->layout;
	}

	public function getComputerProcessing() {
		return $this->computerProcessing;
	}

	public function setLayout($layout) {
		$this->layout = $layout;
		return $this;
	}

	public function getCoverLayout() {
		return $this->coverLayout;
	}

	public function setCoverLayout($coverLayout) {
		$this->coverLayout = $coverLayout;
		return $this;
	}

	public function setComputerProcessing($computerProcessing) {
		$this->computerProcessing = $computerProcessing;
		return $this;
	}

	public function getPrepress() {
		return $this->prepress;
	}

	public function setCompiler($compiler) {
		$this->compiler = $compiler;
		return $this;
	}

	public function setPrepress($prepress) {
		$this->prepress = $prepress;
		return $this;
	}

	public function setEditor($editor) {
		$this->editor = $editor;
		return $this;
	}

	public function getChiefEditor() {
		return $this->chiefEditor;
	}

	public function setChiefEditor($chiefEditor) {
		$this->chiefEditor = $chiefEditor;
		return $this;
	}

	public function getEditorialStaff() {
		return $this->editorialStaff;
	}

	public function setEditorialStaff($editorialStaff) {
		$this->editorialStaff = $editorialStaff;
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

	public function setNbScans($nbScans) {
		$this->nbScans = $nbScans;
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

	public function getPublisherNumber() {
		return $this->publisherNumber;
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

	public function setPublisherNumber($publisherNumber) {
		$this->publisherNumber = $publisherNumber;
		return $this;
	}

	public function getIsbn10() {
		return $this->isbn10;
	}

	public function getIsbn13() {
		return $this->isbn13;
	}

	public function setIsbn10($isbn10) {
		$this->isbn10 = $isbn10;
		return $this;
	}

	public function setIsbn13($isbn13) {
		$this->isbn13 = $isbn13;
		return $this;
	}

	public function getSource() {
		return $this->source;
	}

	public function getCreatedBy() {
		return $this->createdBy;
	}

	public function setSource($source) {
		$this->source = $source;
		return $this;
	}

	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		return $this;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
		$this->setUpdatedAt($createdAt);
		return $this;
	}

	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
		return $this;
	}

	public function getMarketingSnippets() {
		return $this->marketingSnippets;
	}

	public function setMarketingSnippets($marketingSnippets) {
		$this->marketingSnippets = $marketingSnippets;
		return $this;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
	 */
	public function setCoverFile(File $image = null) {
		$this->coverFile = $image;
		$this->setUpdatedAtOnImage($image);
	}

	/**
	 * @return File
	 */
	public function getCoverFile() {
		return $this->coverFile;
	}

	/**
	 * @param string $cover
	 */
	public function setCover($cover) {
		$this->cover = $cover;
	}

	/**
	 * @return string
	 */
	public function getCover() {
		return $this->cover;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
	 */
	public function setBackCoverFile(File $image = null) {
		$this->backCoverFile = $image;
		$this->setUpdatedAtOnImage($image);
	}

	/**
	 * @return File
	 */
	public function getBackCoverFile() {
		return $this->backCoverFile;
	}

	/**
	 * @param string $backCover
	 */
	public function setBackCover($backCover) {
		$this->backCover = $backCover;
	}

	/**
	 * @return string
	 */
	public function getBackCover() {
		return $this->backCover;
	}

	public function setScan1File(File $image = null) { $this->scan1File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan1File() { return $this->scan1File; }
	public function setScan1($scan) { $this->scan1 = $scan; $this->incNbScans(); }
	public function getScan1() { return $this->scan1; }
	public function setScan2File(File $image = null) { $this->scan2File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan2File() { return $this->scan2File; }
	public function setScan2($scan) { $this->scan2 = $scan; $this->incNbScans(); }
	public function getScan2() { return $this->scan2; }
	public function setScan3File(File $image = null) { $this->scan3File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan3File() { return $this->scan3File; }
	public function setScan3($scan) { $this->scan3 = $scan; $this->incNbScans(); }
	public function getScan3() { return $this->scan3; }
	public function setScan4File(File $image = null) { $this->scan4File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan4File() { return $this->scan4File; }
	public function setScan4($scan) { $this->scan4 = $scan; $this->incNbScans(); }
	public function getScan4() { return $this->scan4; }
	public function setScan5File(File $image = null) { $this->scan5File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan5File() { return $this->scan5File; }
	public function setScan5($scan) { $this->scan5 = $scan; $this->incNbScans(); }
	public function getScan5() { return $this->scan5; }
	public function setScan6File(File $image = null) { $this->scan6File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan6File() { return $this->scan6File; }
	public function setScan6($scan) { $this->scan6 = $scan; $this->incNbScans(); }
	public function getScan6() { return $this->scan6; }
	public function setScan7File(File $image = null) { $this->scan7File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan7File() { return $this->scan7File; }
	public function setScan7($scan) { $this->scan7 = $scan; $this->incNbScans(); }
	public function getScan7() { return $this->scan7; }
	public function setScan8File(File $image = null) { $this->scan8File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan8File() { return $this->scan8File; }
	public function setScan8($scan) { $this->scan8 = $scan; $this->incNbScans(); }
	public function getScan8() { return $this->scan8; }
	public function setScan9File(File $image = null) { $this->scan9File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan9File() { return $this->scan9File; }
	public function setScan9($scan) { $this->scan9 = $scan; $this->incNbScans(); }
	public function getScan9() { return $this->scan9; }
	public function setScan10File(File $image = null) { $this->scan10File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan10File() { return $this->scan10File; }
	public function setScan10($scan) { $this->scan10 = $scan; $this->incNbScans(); }
	public function getScan10() { return $this->scan10; }
	public function setScan11File(File $image = null) { $this->scan11File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan11File() { return $this->scan11File; }
	public function setScan11($scan) { $this->scan11 = $scan; $this->incNbScans(); }
	public function getScan11() { return $this->scan11; }
	public function setScan12File(File $image = null) { $this->scan12File = $image; $this->setUpdatedAtOnImage($image); }
	public function getScan12File() { return $this->scan12File; }
	public function setScan12($scan) { $this->scan12 = $scan; $this->incNbScans(); }
	public function getScan12() { return $this->scan12; }

	protected function setUpdatedAtOnImage($image) {
		if ($image && $image instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->setUpdatedAt(new \DateTime());
		}
	}

	public function getChitankaId() {
		return $this->chitankaId;
	}

	public function setChitankaId($chitankaId) {
		$this->chitankaId = $chitankaId;
	}

	public function getToc() {
		return $this->toc;
	}

	public function setToc($toc) {
		$this->toc = $toc;
		return $this;
	}

	public function getTranslatedFromLanguage() {
		return $this->translatedFromLanguage;
	}

	public function setTranslatedFromLanguage($translatedFromLanguage) {
		$this->translatedFromLanguage = $translatedFromLanguage;
		return $this;
	}
	public function getDateOfTranslation() {
		return $this->dateOfTranslation;
	}

	public function setDateOfTranslation($dateOfTranslation) {
		$this->dateOfTranslation = $dateOfTranslation;
		return $this;
	}

	public function getTotalPrint() {
		return $this->totalPrint;
	}

	public function setTotalPrint($totalPrint) {
		$this->totalPrint = $totalPrint;
		return $this;
	}

	public function getLitGroup() {
		return $this->litGroup;
	}

	public function setLitGroup($litGroup) {
		$this->litGroup = $litGroup;
		return $this;
	}
	public function getTrackingCode() {
		return $this->trackingCode;
	}

	public function setTrackingCode($trackingCode) {
		$this->trackingCode = $trackingCode;
		return $this;
	}

	public function getUniformProductClassification() {
		return $this->uniformProductClassification;
	}

	public function setUniformProductClassification($uniformProductClassification) {
		$this->uniformProductClassification = $uniformProductClassification;
		return $this;
	}

	public function getNotesAboutOriginal() {
		return $this->notesAboutOriginal;
	}

	public function setNotesAboutOriginal($notesAboutOriginal) {
		$this->notesAboutOriginal = $notesAboutOriginal;
		return $this;
	}

	public function isIncomplete() {
		return $this->isIncomplete;
	}

	public function setIsIncomplete($isIncomplete) {
		$this->isIncomplete = $isIncomplete;
	}

	public function setLock($user) {
		$this->lockedBy = $user;
		$this->lockedAt = new \DateTime();
	}

	public function clearLock() {
		$this->lockedBy = null;
		$this->lockedAt = null;
	}

	public function isLockedForUser($user) {
		return $this->lockedBy !== null && $this->lockedBy !== $user && !$this->isLockExpired();
	}

	public function isLockExpired() {
		return $this->lockedAt === null || (time() - $this->lockedAt->getTimeStamp() > self::LOCK_EXPIRE_TIME);
	}

	public function getLockedBy() {
		return $this->lockedBy;
	}

	/** @ORM\PrePersist */
	public function onPreInsert() {
		$this->setCreatedAt(new \DateTime);
		$this->updateNbScans();
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate() {
		$this->setUpdatedAt(new \DateTime);
		$this->updateNbScans();
	}

	protected function updateNbScans() {
		$nbScans = 0;
		foreach (range(1, 12) as $i) {
			if ($this->{"scan$i"} !== null) {
				$nbScans++;
			}
		}
		$this->setNbScans($nbScans);
	}

	protected function incNbScans() {
		$this->setNbScans($this->getNbScans() + 1);
	}

	/** @return BookRevision */
	public function createRevision() {
		$revision = new BookRevision();
		$revision->setBook($this);
		$revision->setCreatedAt(new \DateTime());
		return $revision;
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'subtitle' => $this->subtitle,
			'author' => $this->author,
			'translator' => $this->translator,
			'translatedFromLanguage' => $this->translatedFromLanguage,
			'dateOfTranslation' => $this->dateOfTranslation,
			'compiler' => $this->compiler,
			'chiefEditor' => $this->chiefEditor,
			'editor' => $this->editor,
			'editorialStaff' => $this->editorialStaff,
			'publisherEditor' => $this->publisherEditor,
			'artistEditor' => $this->artistEditor,
			'technicalEditor' => $this->technicalEditor,
			'consultant' => $this->consultant,
			'reviewer' => $this->reviewer,
			'artist' => $this->artist,
			'corrector' => $this->corrector,
			'layout' => $this->layout,
			'coverLayout' => $this->coverLayout,
			'computerProcessing' => $this->computerProcessing,
			'prepress' => $this->prepress,
			'contentType' => $this->contentType,
			'publisher' => $this->publisher,
			'classifications' => $this->classifications,
			'sequence' => $this->sequence,
			'sequenceNr' => $this->sequenceNr,
			'infoSources' => $this->infoSources,
			'works' => $this->works,
			'pubCity' => $this->pubCity,
			'pubDate' => $this->pubDate,
			'publisherAddress' => $this->publisherAddress,
			'nationality' => $this->nationality,
			'edition' => $this->edition,
			'litGroup' => $this->litGroup,
			'print' => $this->print,
			'typeSettingIn' => $this->typeSettingIn,
			'printSigned' => $this->printSigned,
			'printOut' => $this->printOut,
			'printerSheets' => $this->printerSheets,
			'publisherSheets' => $this->publisherSheets,
			'provisionPublisherSheets' => $this->provisionPublisherSheets,
			'format' => $this->format,
			'publisherCode' => $this->publisherCode,
			'publisherOrder' => $this->publisherOrder,
			'publisherNumber' => $this->publisherNumber,
			'trackingCode' => $this->trackingCode,
			'uniformProductClassification' => $this->uniformProductClassification,
			'totalPrint' => $this->totalPrint,
			'pageCount' => $this->pageCount,
			'price' => $this->price,
			'binding' => $this->binding,
			'language' => $this->language,
			'illustrated' => $this->illustrated,
			'isbn10' => $this->isbn10,
			'isbn13' => $this->isbn13,
			'notes' => $this->notes,
			'notesAboutOriginal' => $this->notesAboutOriginal,
			'nbScans' => $this->nbScans,
			'source' => $this->source,
			'verified' => $this->verified,
			'annotation' => $this->annotation,
			'marketingSnippets' => $this->marketingSnippets,
			'toc' => $this->toc,
			'themes' => $this->themes,
			'genre' => $this->genre,
			'category' => $this->category,
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'scan1' => $this->scan1,
			'scan2' => $this->scan2,
			'scan3' => $this->scan3,
			'scan4' => $this->scan4,
			'scan5' => $this->scan5,
			'scan6' => $this->scan6,
			'scan7' => $this->scan7,
			'scan8' => $this->scan8,
			'scan9' => $this->scan9,
			'scan10' => $this->scan10,
			'scan11' => $this->scan11,
			'scan12' => $this->scan12,
			'chitankaId' => $this->chitankaId,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
		];
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->toArray();
	}

	public function getDifferences(Book $book) {
		$ourFields = $this->toArray();
		$otherFields = $book->toArray();
		$diffs = [];
		foreach ($ourFields as $field => $ourValue) {
			if ($ourValue !== $otherFields[$field]) {
				$diffs[$field] = [$ourValue, $otherFields[$field]];
			}
		}
		return $diffs;
	}
}
