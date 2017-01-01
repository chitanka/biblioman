<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

	const LOCK_EXPIRE_TIME = 7200; // 2 hours

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

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
	 * @ORM\Column(type="string", length=255, nullable=true)
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
	 * @ORM\Column(type="string", length=500, nullable=true)
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
	private $works;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publisherCity;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publishingDate;

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
	 * @ORM\ManyToOne(targetEntity="BookCategory")
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

	/**
	 * @var BookScan[]
	 * @ORM\OneToMany(targetEntity="BookScan", mappedBy="book", cascade={"persist"})
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	private $scans;

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
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	private $updatedTrackingEnabled = true;

	/**
	 * @var BookRevision[]
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

	public function __construct() {
		$this->revisions = new ArrayCollection();
		$this->links = new ArrayCollection();
	}

	public function __toString() {
		return $this->getTitle();
	}

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

	public function getPublisherCity() {
		return $this->publisherCity;
	}

	public function getPublishingDate() {
		return $this->publishingDate;
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

	public function getAltTitle() {
		return $this->altTitle;
	}

	public function setAltTitle($altTitle) {
		$this->altTitle = $altTitle;
	}

	public function getSubtitle() {
		return $this->subtitle;
	}

	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
		return $this;
	}

	public function getSubtitle2() {
		return $this->subtitle2;
	}

	public function setSubtitle2($subtitle2) {
		$this->subtitle2 = $subtitle2;
		return $this;
	}

	public function getVolumeTitle() {
		return $this->volumeTitle;
	}

	public function setVolumeTitle($volumeTitle) {
		$this->volumeTitle = $volumeTitle;
	}

	public function getOtherAuthors() {
		return $this->otherAuthors;
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

	public function setOtherAuthors($authors) {
		$this->otherAuthors = $authors;
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

	public function getIllustrator() {
		return $this->illustrator;
	}

	public function setIllustrator($illustrator) {
		$this->illustrator = $illustrator;
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

	public function setPublisherCity($publisherCity) {
		$this->publisherCity = $publisherCity;
		return $this;
	}

	public function setPublishingDate($publishingDate) {
		$this->publishingDate = $publishingDate;
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

	public function getIsbn() {
		return $this->isbn;
	}

	public function getIsbnClean() {
		return $this->isbnClean;
	}

	public function setIsbn($isbn) {
		$this->isbn = self::normalizeIsbn($isbn);
		$this->setIsbnClean(self::normalizeSearchableIsbn($this->isbn));
	}

	public function setIsbnClean($isbnClean) {
		$this->isbnClean = $isbnClean;
	}

	public function getCreatedBy() {
		return $this->createdBy;
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

	public function disableUpdatedTracking() {
		$this->updatedTrackingEnabled = false;
	}

	public function getMarketingSnippets() {
		return $this->marketingSnippets;
	}

	public function setMarketingSnippets($marketingSnippets) {
		$this->marketingSnippets = $marketingSnippets;
		return $this;
	}

	/**
	 * @return BookLink[]
	 */
	public function getLinks() {
		return $this->links;
	}

	/**
	 * @param BookLink[] $links
	 */
	public function setLinks($links) {
		$this->links = $links;
	}

	public function addLink(BookLink $link) {
		if (!empty($link->getUrl())) {
			$link->setBook($this);
			$this->links[] = $link;
		}
	}

	public function removeLink(BookLink $link) {
		$this->links->removeElement($link);
	}

	/**
	 * @return BookLink[][]
	 */
	public function getLinksByCategory() {
		$linksByCategory = [];
		foreach ($this->getLinks() as $link) {
			$linksByCategory[$link->getCategory()][] = $link;
		}
		$linksByCategorySorted = array_filter(array_replace(array_fill_keys(BookLink::$categories, null), $linksByCategory));
		return $linksByCategorySorted;
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

	/**
	 * @return BookScan[]
	 */
	public function getScans() {
		return $this->scans;
	}

	/**
	 * @param BookScan[] $scans
	 */
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

	protected function setUpdatedAtOnImage($image) {
		if ($image && $image instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->setUpdatedAt(new \DateTime());
		}
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

	public function getUniversalDecimalClassification() {
		return $this->universalDecimalClassification;
	}

	public function setUniversalDecimalClassification($universalDecimalClassification) {
		$this->universalDecimalClassification = $universalDecimalClassification;
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

	public function getReasonWhyIncomplete() {
		return $this->reasonWhyIncomplete;
	}

	public function setReasonWhyIncomplete($reason) {
		$this->reasonWhyIncomplete = $reason;
	}

	public function getAdminComment() {
		return $this->adminComment;
	}

	public function setAdminComment($reason) {
		$this->adminComment = $reason;
	}

	public function getOcredText() {
		return $this->ocredText;
	}

	public function setOcredText($reason) {
		$this->ocredText = $reason;
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
		if ($this->updatedTrackingEnabled) {
			$this->setUpdatedAt(new \DateTime);
			$this->updateNbScans();
		}
	}

	protected function updateNbScans() {
		$this->setNbScans(count($this->scans));
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
			'altTitle' => $this->altTitle,
			'subtitle' => $this->subtitle,
			'subtitle2' => $this->subtitle2,
			'volumeTitle' => $this->volumeTitle,
			'author' => $this->author,
			'translator' => $this->translator,
			'translatedFromLanguage' => $this->translatedFromLanguage,
			'dateOfTranslation' => $this->dateOfTranslation,
			'otherAuthors' => $this->otherAuthors,
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
			'illustrator' => $this->illustrator,
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
			'works' => $this->works,
			'publisherCity' => $this->publisherCity,
			'publishingDate' => $this->publishingDate,
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
			'isbn' => $this->isbn,
			'notes' => $this->notes,
			'notesAboutOriginal' => $this->notesAboutOriginal,
			'nbScans' => $this->nbScans,
			'verified' => $this->verified,
			'annotation' => $this->annotation,
			'marketingSnippets' => $this->marketingSnippets,
			'toc' => $this->toc,
			'themes' => $this->themes,
			'genre' => $this->genre,
			'category' => $this->category,
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
			'infoSources' => $this->infoSources,
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
		$excludedFields = ['updatedAt', 'nbScans'];
		foreach ($ourFields as $field => $ourValue) {
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
		return $value;
	}

	public static function normalizePerson($name) {
		$nameNormalized = $name;
		$prefixes = [
			'д-р',
			'проф.',
			'проф. д-р',
			'акад.',
		];
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp($prefixes).') /u', '', $nameNormalized);
		return $nameNormalized;
	}

	public static function normalizePublisher($name) {
		$nameNormalized = $name;
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
		]);
		$nameNormalized = trim($nameNormalized, ' ,-—');
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
}
