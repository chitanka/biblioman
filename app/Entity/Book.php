<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Book extends Entity implements \JsonSerializable {

	const STATE_INCOMPLETE = 'incomplete';
	const STATE_VERIFIED_0 = 'verified_0';
	const STATE_VERIFIED_1 = 'verified_1';
	const STATE_VERIFIED_2 = 'verified_2';
	const STATE_VERIFIED_3 = 'verified_3';

	const LOCK_EXPIRE_TIME = 3600; // 1 hour

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $altTitle;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $subtitle;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $subtitle2;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $volumeTitle;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $author;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
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
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $adaptedBy;

	/**
	 * @ORM\Column(type="string", length=700, nullable=true)
	 */
	private $otherAuthors;

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
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $sequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $subsequence;

	/**
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $subsequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $series;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	private $seriesNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publisherCity;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publishingYear;

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
	 * @ORM\Column(type="string", length=30, nullable=true)
	 */
	private $universalDecimalClassification;

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
	private $language = 'български';

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $illustrated;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $isbn;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $isbnClean;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $otherFields;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notes;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notesAboutOriginal;

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
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $themes;

	/**
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $genre;

	/**
	 * @ORM\ManyToOne(targetEntity="BookCategory", fetch="EAGER")
	 */
	private $category;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $fullContent;

	/**
	 * @Vich\UploadableField(mapping="fullcontent", fileNameProperty="fullContent")
	 * @var File
	 */
	private $fullContentFile;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $cover;

	/**
	 * @Vich\UploadableField(mapping="cover", fileNameProperty="cover")
	 * @var File
	 */
	private $coverFile;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $backCover;

	/**
	 * @Vich\UploadableField(mapping="cover", fileNameProperty="backCover")
	 * @var File
	 */
	private $backCoverFile;

	/**
	 * @var BookCover[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookCover", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	private $covers;

	/**
	 * Number of uploaded covers for the book
	 * @ORM\Column(type="smallint")
	 */
	private $nbCovers;

	/**
	 * Temporary storage for new covers, uploaded through the special fields
	 * @var array
	 */
	private $newCovers = [];

	/**
	 * @var BookScan[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookScan", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	private $scans;

	/**
	 * Number of uploaded scans for the book
	 * @ORM\Column(type="smallint")
	 */
	private $nbScans;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $infoSources;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $adminComment;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $ocredText;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $createdBy;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	private $updatedTrackingEnabled = true;

	/**
	 * @var BookRevision[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookRevision", mappedBy="book")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
	 */
	private $revisions;

	/**
	 * @var BookLink[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookLink", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 */
	private $links;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	private $isIncomplete = true;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $reasonWhyIncomplete;

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

	/**
	 * @var BookOnShelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookOnShelf", mappedBy="book", fetch="EXTRA_LAZY")
	 */
	private $booksOnShelf;

	/**
	 * @var Shelf[]|ArrayCollection
	 */
	private $shelves;

	public function __construct() {
		$this->revisions = new ArrayCollection();
		$this->links = new ArrayCollection();
		$this->scans = new ArrayCollection();
		$this->covers = new ArrayCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
	}

	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = $this->typoReplace($title); }
	public function getAltTitle() { return $this->altTitle; }
	public function setAltTitle($altTitle) { $this->altTitle = $this->typoReplace($altTitle); }
	public function getSubtitle() { return $this->subtitle; }
	public function setSubtitle($subtitle) { $this->subtitle = $this->typoReplace($subtitle); }
	public function getSubtitle2() { return $this->subtitle2; }
	public function setSubtitle2($subtitle2) { $this->subtitle2 = $this->typoReplace($subtitle2); }
	public function getVolumeTitle() { return $this->volumeTitle; }
	public function setVolumeTitle($volumeTitle) { $this->volumeTitle = $this->typoReplace($volumeTitle); }
	public function getAuthor() { return $this->author; }
	public function setAuthor($author) { $this->author = $author; }
	public function getTranslator() { return $this->translator; }
	public function setTranslator($translator) { $this->translator = $translator; }
	public function getTranslatedFromLanguage() { return $this->translatedFromLanguage; }
	public function setTranslatedFromLanguage($translatedFromLanguage) { $this->translatedFromLanguage = $translatedFromLanguage; }
	public function getDateOfTranslation() { return $this->dateOfTranslation; }
	public function setDateOfTranslation($dateOfTranslation) { $this->dateOfTranslation = $dateOfTranslation; }
	public function getAdaptedBy() { return $this->adaptedBy; }
	public function setAdaptedBy($adaptedBy) { $this->adaptedBy = $adaptedBy; }
	public function getOtherAuthors() { return $this->otherAuthors; }
	public function setOtherAuthors($otherAuthors) { $this->otherAuthors = $otherAuthors; }
	public function getCompiler() { return $this->compiler; }
	public function setCompiler($compiler) { $this->compiler = $compiler; }
	public function getChiefEditor() { return $this->chiefEditor; }
	public function setChiefEditor($chiefEditor) { $this->chiefEditor = $chiefEditor; }
	public function getManagingEditor() { return $this->managingEditor; }
	public function setManagingEditor($managingEditor) { $this->managingEditor = $managingEditor; }
	public function getEditor() { return $this->editor; }
	public function setEditor($editor) { $this->editor = $editor; }
	public function getEditorialStaff() { return $this->editorialStaff; }
	public function setEditorialStaff($editorialStaff) { $this->editorialStaff = $editorialStaff; }
	public function getPublisherEditor() { return $this->publisherEditor; }
	public function setPublisherEditor($publisherEditor) { $this->publisherEditor = $publisherEditor; }
	public function getArtistEditor() { return $this->artistEditor; }
	public function setArtistEditor($artistEditor) { $this->artistEditor = $artistEditor; }
	public function getTechnicalEditor() { return $this->technicalEditor; }
	public function setTechnicalEditor($technicalEditor) { $this->technicalEditor = $technicalEditor; }
	public function getConsultant() { return $this->consultant; }
	public function setConsultant($consultant) { $this->consultant = $consultant; }
	public function getScienceEditor() { return $this->scienceEditor; }
	public function setScienceEditor($scienceEditor) { $this->scienceEditor = $scienceEditor; }
	public function getCopyreader() { return $this->copyreader; }
	public function setCopyreader($copyreader) { $this->copyreader = $copyreader; }
	public function getReviewer() { return $this->reviewer; }
	public function setReviewer($reviewer) { $this->reviewer = $reviewer; }
	public function getArtist() { return $this->artist; }
	public function setArtist($artist) { $this->artist = $artist; }
	public function getIllustrator() { return $this->illustrator; }
	public function setIllustrator($illustrator) { $this->illustrator = $illustrator; }
	public function getCorrector() { return $this->corrector; }
	public function setCorrector($corrector) { $this->corrector = $corrector; }
	public function getLayout() { return $this->layout; }
	public function setLayout($layout) { $this->layout = $layout; }
	public function getCoverLayout() { return $this->coverLayout; }
	public function setCoverLayout($coverLayout) { $this->coverLayout = $this->typoReplace($coverLayout); }
	public function getLibraryDesign() { return $this->libraryDesign; }
	public function setLibraryDesign($libraryDesign) { $this->libraryDesign = $this->typoReplace($libraryDesign); }
	public function getComputerProcessing() { return $this->computerProcessing; }
	public function setComputerProcessing($computerProcessing) { $this->computerProcessing = $this->typoReplace($computerProcessing); }
	public function getPrepress() { return $this->prepress; }
	public function setPrepress($prepress) { $this->prepress = $this->typoReplace($prepress); }
	public function getContentType() { return $this->contentType; }
	public function setContentType($contentType) { $this->contentType = $contentType; }
	public function getPublisher() { return $this->publisher; }
	public function setPublisher($publisher) { $this->publisher = $this->typoReplace($publisher); }
	public function getClassifications() { return $this->classifications; }
	public function setClassifications($classifications) { $this->classifications = $classifications; }
	public function getSequence() { return $this->sequence; }
	public function setSequence($sequence) { $this->sequence = $this->typoReplace($sequence); }
	public function getSequenceNr() { return $this->sequenceNr; }
	public function setSequenceNr($sequenceNr) { $this->sequenceNr = $sequenceNr; }
	public function getSubsequence() { return $this->subsequence; }
	public function setSubsequence($subsequence) { $this->subsequence = $this->typoReplace($subsequence); }
	public function getSubsequenceNr() { return $this->subsequenceNr; }
	public function setSubsequenceNr($subsequenceNr) { $this->subsequenceNr = $subsequenceNr; }
	public function getSeries() { return $this->series; }
	public function setSeries($series) { $this->series = $this->typoReplace($series); }
	public function getSeriesNr() { return $this->seriesNr; }
	public function setSeriesNr($seriesNr) { $this->seriesNr = $seriesNr; }
	public function getPublisherCity() { return $this->publisherCity; }
	public function setPublisherCity($publisherCity) { $this->publisherCity = $publisherCity; }
	public function getPublishingYear() { return $this->publishingYear; }
	public function setPublishingYear($publishingYear) { $this->publishingYear = $publishingYear; }
	public function getPublisherAddress() { return $this->publisherAddress; }
	public function setPublisherAddress($publisherAddress) { $this->publisherAddress = $this->typoReplace($publisherAddress); }
	public function getNationality() { return $this->nationality; }
	public function setNationality($nationality) { $this->nationality = $nationality; }
	public function getEdition() { return $this->edition; }
	public function setEdition($edition) { $this->edition = $edition; }
	public function getLitGroup() { return $this->litGroup; }
	public function setLitGroup($litGroup) { $this->litGroup = $litGroup; }
	public function getPrint() { return $this->print; }
	public function setPrint($print) { $this->print = $this->typoReplace($print); }
	public function getTypeSettingIn() { return $this->typeSettingIn; }
	public function setTypeSettingIn($typeSettingIn) { $this->typeSettingIn = $typeSettingIn; }
	public function getPrintSigned() { return $this->printSigned; }
	public function setPrintSigned($printSigned) { $this->printSigned = $printSigned; }
	public function getPrintOut() { return $this->printOut; }
	public function setPrintOut($printOut) { $this->printOut = $printOut; }
	public function getPrinterSheets() { return $this->printerSheets; }
	public function setPrinterSheets($printerSheets) { $this->printerSheets = $printerSheets; }
	public function getPublisherSheets() { return $this->publisherSheets; }
	public function setPublisherSheets($publisherSheets) { $this->publisherSheets = $publisherSheets; }
	public function getProvisionPublisherSheets() { return $this->provisionPublisherSheets; }
	public function setProvisionPublisherSheets($provisionPublisherSheets) { $this->provisionPublisherSheets = $provisionPublisherSheets; }
	public function getFormat() { return $this->format; }
	public function setFormat($format) { $this->format = Typograph::replaceTimesChar($format); }
	public function getPublisherCode() { return $this->publisherCode; }
	public function setPublisherCode($publisherCode) { $this->publisherCode = $publisherCode; }
	public function getPublisherOrder() { return $this->publisherOrder; }
	public function setPublisherOrder($publisherOrder) { $this->publisherOrder = $publisherOrder; }
	public function getPublisherNumber() { return $this->publisherNumber; }
	public function setPublisherNumber($publisherNumber) { $this->publisherNumber = $publisherNumber; }
	public function getTrackingCode() { return $this->trackingCode; }
	public function setTrackingCode($trackingCode) { $this->trackingCode = $trackingCode; }
	public function getUniformProductClassification() { return $this->uniformProductClassification; }
	public function setUniformProductClassification($uniformProductClassification) { $this->uniformProductClassification = $uniformProductClassification; }
	public function getUniversalDecimalClassification() { return $this->universalDecimalClassification; }
	public function setUniversalDecimalClassification($universalDecimalClassification) { $this->universalDecimalClassification = $universalDecimalClassification; }
	public function getTotalPrint() { return $this->totalPrint; }
	public function setTotalPrint($totalPrint) { $this->totalPrint = $totalPrint; }
	public function getPageCount() { return $this->pageCount; }
	public function setPageCount($pageCount) { $this->pageCount = $pageCount; }
	public function getPrice() { return $this->price; }
	public function setPrice($price) { $this->price = $price; }
	public function getBinding() { return $this->binding; }
	public function setBinding($binding) { $this->binding = $binding; }
	public function getLanguage() { return $this->language; }
	public function setLanguage($language) { $this->language = $language; }
	public function getIllustrated() { return $this->illustrated; }
	public function setIllustrated($illustrated) { $this->illustrated = $illustrated; }
	public function getIsbn() { return $this->isbn; }
	public function setIsbn($isbn) { $this->isbn = self::normalizeIsbn($isbn); $this->setIsbnClean(self::normalizeSearchableIsbn($this->isbn)); }
	public function getIsbnClean() { return $this->isbnClean; }
	public function setIsbnClean($isbnClean) { $this->isbnClean = $isbnClean; }
	public function getOtherFields() { return $this->otherFields; }
	public function setOtherFields($otherFields) { $this->otherFields = $this->typoReplace($otherFields); }
	public function getNotes() { return $this->notes; }
	public function setNotes($notes) { $this->notes = $this->typoReplace($notes); }
	public function getNotesAboutOriginal() { return $this->notesAboutOriginal; }
	public function setNotesAboutOriginal($notesAboutOriginal) { $this->notesAboutOriginal = $notesAboutOriginal; }
	public function getVerified() { return $this->verified; }
	public function setVerified($verified) { $this->verified = $verified; }
	public function getAnnotation() { return $this->annotation; }
	public function setAnnotation($annotation) { $this->annotation = $this->typoReplace($annotation); }
	public function getNotesAboutAuthor() { return $this->notesAboutAuthor; }
	public function setNotesAboutAuthor($notesAboutAuthor) { $this->notesAboutAuthor = $this->typoReplace($notesAboutAuthor); }
	public function getMarketingSnippets() { return $this->marketingSnippets; }
	public function setMarketingSnippets($marketingSnippets) { $this->marketingSnippets = $this->typoReplace($marketingSnippets); }
	public function getToc() { return $this->toc; }
	public function setToc($toc) { $this->toc = $this->typoReplace($toc); }
	public function getThemes() { return $this->themes; }
	public function setThemes($themes) { $this->themes = $themes; }
	public function getGenre() { return $this->genre; }
	public function setGenre($genre) { $this->genre = $genre; }
	public function getCategory() { return $this->category; }
	public function setCategory($category) { $this->category = $category; }
	public function getInfoSources() { return $this->infoSources; }
	public function setInfoSources($infoSources) { $this->infoSources = $infoSources; }
	public function getAdminComment() { return $this->adminComment; }
	public function setAdminComment($adminComment) { $this->adminComment = $adminComment; }
	public function getOcredText() { return $this->ocredText; }
	public function setOcredText($ocredText) { $this->ocredText = $ocredText; }
	public function getCreatedBy() { return $this->createdBy; }
	public function setCreatedBy($createdBy) { $this->createdBy = $createdBy; }
	public function getCreatedAt() { return $this->createdAt; }
	public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }
	public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
	public function getRevisions() { return $this->revisions; }
	public function setRevisions($revisions) { $this->revisions = $revisions; }
	public function isIncomplete() { return $this->isIncomplete; }
	public function setIsIncomplete($isIncomplete) { $this->isIncomplete = $isIncomplete; }
	public function getReasonWhyIncomplete() { return $this->reasonWhyIncomplete; }
	public function setReasonWhyIncomplete($reasonWhyIncomplete) { $this->reasonWhyIncomplete = $reasonWhyIncomplete; }

	public function getLinks() { return $this->links; }
	/** @param BookLink[] $links */
	public function setLinks($links) { $this->links = $links; }

	public function addLink(BookLink $link) {
		if (!empty($link->getUrl())) {
			$link->setBook($this);
			$this->links[] = $link;
		}
	}

	public function removeLink(BookLink $link) {
		$this->links->removeElement($link);
	}

	/** @return BookLink[][] */
	public function getLinksByCategory() {
		$linksByCategory = [];
		foreach ($this->getLinks() as $link) {
			$linksByCategory[$link->getCategory()][] = $link;
		}
		$linksByCategorySorted = array_filter(array_replace(array_fill_keys(BookLink::$categories, null), $linksByCategory));
		return $linksByCategorySorted;
	}

	public function getBooksOnShelf() { return $this->booksOnShelf; }
	public function setBooksOnShelf($booksOnShelf) { $this->booksOnShelf = $booksOnShelf; }
	public function setShelves($shelves) {
		$this->shelves = $shelves instanceof ArrayCollection ? $shelves : new ArrayCollection($shelves);
	}
	public function getShelves() {
		return $this->shelves ?: $this->shelves = $this->getBooksOnShelf()->map(function(BookOnShelf $bs) {
			return $bs->getShelf();
		});
	}

	public function getState() {
		if ($this->isIncomplete()) {
			return self::STATE_INCOMPLETE;
		}
		return self::STATE_VERIFIED_0;
	}

	public function disableUpdatedTracking() {
		$this->updatedTrackingEnabled = false;
	}

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFullContentFile(File $file = null) { $this->fullContentFile = $file; $this->setUpdatedAtOnFileUpload($file); }
	public function getFullContentFile() { return $this->fullContentFile; }
	public function setFullContent($fullContent) { $this->fullContent = $fullContent; }
	public function getFullContent() { return $this->fullContent; }

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image */
	public function setCoverFile(File $image = null) {
		$this->coverFile = $image;
		if ($image !== null) {
			$this->addCover($this->createCover($image, BookCover::TYPE_FRONT));
			$this->setUpdatedAtOnFileUpload($image);
		}
	}
	public function getCoverFile() { return $this->coverFile; }
	public function setCover($cover) {
		// TODO make it smarter
		$this->cover = str_replace('.tif', '.jpg', $cover);
	}
	public function getCover() { return $this->cover; }

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image */
	public function setBackCoverFile(File $image = null) {
		$this->backCoverFile = $image;
		if ($image !== null) {
			$this->addCover($this->createCover($image, BookCover::TYPE_BACK));
			$this->setUpdatedAtOnFileUpload($image);
		}
	}
	public function getBackCoverFile() { return $this->backCoverFile; }
	public function setBackCover($backCover) {
		// TODO make it smarter
		$this->backCover = str_replace('.tif', '.jpg', $backCover);
	}
	public function getBackCover() { return $this->backCover; }

	/** @return BookCover[] */
	public function getCovers() { return $this->covers; }
	/** @param BookCover[] $covers */
	public function setCovers($covers) { $this->covers = $covers; $this->updateNbCovers(); }

	public function hasOtherCovers() {
		return count($this->getOtherCovers()) > 0;
	}

	public function getOtherCovers() {
		$otherCovers = [];
		$specialCoverNames = [$this->getCover(), $this->getBackCover()];
		foreach ($this->getCovers() as $cover) {
			if (!in_array($cover->getName(), $specialCoverNames)) {
				$otherCovers[] = $cover;
			}
		}
		return $otherCovers;
	}

	/** @param BookCover[] $covers */
	public function setOtherCovers($covers) {
		$coversToKeep = [];
		foreach ($covers as $cover) {
			if ($cover->isNew()) {
				$cover->setBook($this);
				$this->covers[] = $cover;
			} else {
				$coversToKeep[] = $cover->getId();
			}
		}
		foreach ($this->getOtherCovers() as $otherCover) {
			if (!$otherCover->isNew() && !in_array($otherCover->getId(), $coversToKeep)) {
				$this->removeCover($otherCover);
			}
		}
	}

	public function addCover(BookCover $cover) {
		if (!empty($cover->getFile()) && !empty($cover->getName())) {
			$cover->setBook($this);
			$this->covers[] = $cover;
			$this->updateNbCovers();
		}
	}

	public function removeCover(BookCover $cover) {
		$this->covers->removeElement($cover);
		$this->updateNbCovers();
	}

	public function getNbCovers() { return $this->nbCovers; }
	public function setNbCovers($nbCovers) { $this->nbCovers = $nbCovers; }

	protected function updateNbCovers() {
		$this->setNbCovers(count($this->covers));
	}

	protected function createCover(File $image, $type, $title = null) {
		if (isset($this->newCovers[$type])) {
			$cover = $this->newCovers[$type];
			$cover->setName($image->getBasename());
			$cover->setFile($image);
		} else {
			$this->newCovers[$type] = $cover = new BookCover();
			$cover->setFile($image);
			$cover->setType($type);
			$cover->setInternalFormat($image->guessExtension());
			$cover->setTitle($title);
		}
		return $cover;
	}

	/** @return BookScan[] */
	public function getScans() {
		$sortedScans = [];
		foreach ($this->scans as $scan) {
			$key = (int) $scan->getTitle();
			if (isset($sortedScans[$key])) {
				$sortedScans[] = $scan;
			} else {
				$sortedScans[$key] = $scan;
			}
		}
		ksort($sortedScans);
		$sortedScans = array_values($sortedScans);
		return $sortedScans;
	}

	/** @param BookScan[] $scans */
	public function setScans($scans) {
		$this->scans = $scans;
		$this->updateNbScans();
	}

	public function addScan(BookScan $scan) {
		if (!empty($scan->getFile())) {
			$scan->setBook($this);
			$this->scans[] = $scan;
			$this->updateNbScans();
		}
	}

	public function removeScan(BookScan $scan) {
		$this->scans->removeElement($scan);
		$this->updateNbScans();
	}

	public function getNbScans() { return $this->nbScans; }
	public function setNbScans($nbScans) { $this->nbScans = $nbScans; }

	protected function updateNbScans() {
		$this->setNbScans(count($this->scans));
	}

	public function setCreatorByNewScans($user) {
		foreach ($this->getScans() as $scan) {
			if (empty($scan->getId())) {
				$scan->setCreatedBy($user);
			}
		}
		foreach ($this->getCovers() as $cover) {
			if (empty($cover->getId())) {
				$cover->setCreatedBy($user);
			}
		}
		foreach ($this->newCovers as $newCover) {
			$newCover->setCreatedBy($user);
		}
	}

	protected function setUpdatedAtOnFileUpload($image) {
		if ($image && $image instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->setUpdatedAt(new \DateTime());
		}
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
		$this->updateNbCovers();
		$this->updateNbScans();
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate() {
		if ($this->updatedTrackingEnabled) {
			$this->setUpdatedAt(new \DateTime);
			$this->updateNbCovers();
			$this->updateNbScans();
		}
	}

	public function hasRevisions() {
		return count($this->getRevisions()) > 0;
	}

	public function getRevisionEditors() {
		$editors = [];
		foreach ($this->getRevisions() as $revision) {
			$editors[] = $revision->getCreatedBy();
		}
		return array_unique($editors);
	}

	/** @return BookRevision */
	public function createRevision() {
		$revision = new BookRevision();
		$revision->setBook($this);
		$revision->setCreatedAt(new \DateTime());
		return $revision;
	}

	public function createRevisionIfNecessary(Book $oldBook, $user) {
		$diffs = $oldBook->getDifferences($this);
		if (empty($diffs)) {
			return null;
		}
		if ($user == $this->getCreatedBy() && ((time() - $this->getUpdatedAt()->getTimestamp()) < 3600) && !$this->hasRevisions()) {
			return null;
		}
		$revision = $this->createRevision();
		$revision->setDiffs($diffs);
		$revision->setCreatedBy($user);
		return $revision;
	}

	public function __toString() {
		return $this->getTitle();
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'altTitle' => $this->altTitle,
			'subtitle' => $this->subtitle,
			'subtitle2' => $this->subtitle2,
			'volumeTitle' => $this->volumeTitle,
			'author' => $this->author,
			'translator' => $this->translator,
			'translatedFromLanguage' => $this->translatedFromLanguage,
			'dateOfTranslation' => $this->dateOfTranslation,
			'adaptedBy' => $this->adaptedBy,
			'otherAuthors' => $this->otherAuthors,
			'compiler' => $this->compiler,
			'chiefEditor' => $this->chiefEditor,
			'managingEditor' => $this->managingEditor,
			'editor' => $this->editor,
			'editorialStaff' => $this->editorialStaff,
			'publisherEditor' => $this->publisherEditor,
			'artistEditor' => $this->artistEditor,
			'technicalEditor' => $this->technicalEditor,
			'consultant' => $this->consultant,
			'reviewer' => $this->reviewer,
			'scienceEditor' => $this->scienceEditor,
			'copyreader' => $this->copyreader,
			'artist' => $this->artist,
			'illustrator' => $this->illustrator,
			'corrector' => $this->corrector,
			'layout' => $this->layout,
			'coverLayout' => $this->coverLayout,
			'libraryDesign' => $this->libraryDesign,
			'computerProcessing' => $this->computerProcessing,
			'prepress' => $this->prepress,
			'contentType' => $this->contentType,
			'publisher' => $this->publisher,
			'classifications' => $this->classifications,
			'sequence' => $this->sequence,
			'sequenceNr' => $this->sequenceNr,
			'subsequence' => $this->subsequence,
			'subsequenceNr' => $this->subsequenceNr,
			'series' => $this->series,
			'seriesNr' => $this->seriesNr,
			'publisherCity' => $this->publisherCity,
			'publishingYear' => $this->publishingYear,
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
			'universalDecimalClassification' => $this->universalDecimalClassification,
			'totalPrint' => $this->totalPrint,
			'pageCount' => $this->pageCount,
			'price' => $this->price,
			'binding' => $this->binding,
			'language' => $this->language,
			'illustrated' => $this->illustrated,
			'otherFields' => $this->otherFields,
			'isbn' => $this->isbn,
			'notes' => $this->notes,
			'notesAboutOriginal' => $this->notesAboutOriginal,
			'nbScans' => $this->nbScans,
			'verified' => $this->verified,
			'annotation' => $this->annotation,
			'notesAboutAuthor' => $this->getNotesAboutAuthor(),
			'marketingSnippets' => $this->marketingSnippets,
			'toc' => $this->toc,
			'themes' => $this->themes,
			'genre' => $this->genre,
			'category' => $this->category,
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->getCreatedAt(),
			'updatedAt' => $this->getUpdatedAt(),
			'infoSources' => $this->infoSources,
			'scans' => $this->getScans(),
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

	public function __clone() {
		$this->scans = clone $this->scans;
	}

	public function getDifferences(Book $book) {
		$ourFields = $this->toArray();
		$otherFields = $book->toArray();
		$diffs = [];
		$excludedFields = ['updatedAt', 'nbScans'];
		foreach ($ourFields as $field => $ourValue) {
			if ($ourValue instanceof \Doctrine\ORM\PersistentCollection || is_array($ourValue)) {
				continue;
			}
			if (!in_array($field, $excludedFields) && $ourValue !== $otherFields[$field]) {
				$diffs[$field] = [(string) $ourValue, (string) $otherFields[$field]];
			}
		}
		return $diffs;
	}

	public static function normalizedFieldValue($field, $value) {
		$value = self::normalizeGenericValue($value);
		switch ($field) {
			case 'author':
			case 'otherAuthors':
			case 'adaptedBy':
			case 'translator':
			case 'compiler':
			case 'editorialStaff':
			case 'chiefEditor':
			case 'editor':
			case 'publisherEditor':
			case 'consultant':
			case 'artist':
			case 'artistEditor':
			case 'technicalEditor':
			case 'reviewer':
			case 'corrector':
				return self::normalizePerson($value);
			case 'publisher':
				return self::normalizePublisher($value);
			case 'illustrated':
				return self::normalizeIllustrated($value);
			case 'isbn':
				return self::normalizeIsbn($value);
			case 'isbnClean':
				return self::normalizeSearchableIsbn($value);
		}
		$value = trim($value);
		return $value;
	}

	public static function normalizePerson($name) {
		$nameNormalized = $name;
		$prefixes = [
			'д-р',
			'проф.',
			'проф. д-р',
			'акад.',
			'инж.',
		];
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp($prefixes).') /u', '', $nameNormalized);
		$nameNormalized = preg_replace('/ \(.+\)$/', '', $nameNormalized);
		return $nameNormalized;
	}

	public static function normalizePublisher($name) {
		$nameNormalized = trim($name);
		$prefixes = [
			'Издателска къща',
			'ИК',
			'Издателство',
			'Издателска компания',
			'Издателска група',
			'Книгоиздателска къща',
			'КК',
			'Държавно издателство',
			'ДИ',
			'ДФ',
		];
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp($prefixes).') ["„«]?/u', '', $nameNormalized);
		$nameNormalized = strtr($nameNormalized, [
			'"' => '',
			'„' => '',
			'“' => '',
			'«' => '',
			'»' => '',
			' ООД' => '',
			' ЕООД' => '',
			' АД' => '',
			'Издателство на ЦК на ДКМС' => '',
			'издателство на ЦК на ДКМС' => '',
			'Университетско издателство' => '',
			'Ltd' => '',
			' —' => '',
		]);
		$nameNormalized = trim($nameNormalized, ' ,-');
		if (empty($nameNormalized)) {
			// we do not want to be perfect
			return $name;
		}
		return $nameNormalized;
	}

	private static function normalizeGenericValue($value) {
		return preg_replace('/ \(не е указан[ао]?|не е посочен[ао]?\)/u', '', $value);
	}

	private static function normalizeIllustrated($value) {
		return in_array($value, ['да', '1', 'true']) ? 1 : 0;
	}

	private static function normalizeIsbn($isbn) {
		$isbnFixed = strtr($isbn, [
			'Х' => 'X', // replace cyrillic Х
			'–' => '-',
			'—' => '-',
		]);
		return $isbnFixed;
	}

	private static function normalizeSearchableIsbn($isbn) {
		return preg_replace('/[^\dX,]/', '', self::normalizeIsbn($isbn));
	}

	private static function gluePrefixesForRegExp($prefixes) {
		return implode('|', array_map('preg_quote', $prefixes));
	}

	private function typoReplace($string) {
		return Typograph::replaceAll($string);
	}
}
