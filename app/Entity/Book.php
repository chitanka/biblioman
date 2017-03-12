<?php namespace App\Entity;

use App\Collection\BookCoverCollection;
use App\Collection\BookScanCollection;
use App\Collection\EntityCollection;
use App\Editing\Editor;
use Chitanka\Utils\Typograph;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="App\Entity\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Book extends Entity {

	const STATE_INCOMPLETE = 'incomplete';
	const STATE_VERIFIED_0 = 'verified_0';
	const STATE_VERIFIED_1 = 'verified_1';
	const STATE_VERIFIED_2 = 'verified_2';
	const STATE_VERIFIED_3 = 'verified_3';

	const LOCK_EXPIRE_TIME = 3600; // 1 hour
	const ALLOWED_EDIT_TIME_WO_REVISION = 3600; // 1 hour

	use BookAuthorship { toArray as private authorshipToArray; }
	use BookBody { toArray as private bodyToArray; }
	use BookClassification { toArray as private classificationToArray; }
	use BookContent { toArray as private contentToArray; }
	use BookGrouping { toArray as private groupingToArray; }
	use BookMeta { toArray as private metaToArray; }
	use BookPrint { toArray as private printToArray; }
	use BookPublishing { toArray as private publishingToArray; }
	use BookStaff { toArray as private staffToArray; }
	use BookTitling { toArray as private titlingToArray; }

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $otherFields;

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
	 * @var BookCover[]|BookCoverCollection
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
	 * @var BookCoverCollection|array
	 */
	private $newCovers = [];

	/**
	 * @var BookScan[]|BookScanCollection
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
	 * @var BookLink[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookLink", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 */
	private $links;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $createdBy;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="update")
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
		$this->scans = new BookScanCollection();
		$this->covers = new BookCoverCollection();
		$this->newCovers = new BookCoverCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
	}

	public function getOtherFields() { return $this->otherFields; }
	public function setOtherFields($otherFields) { $this->otherFields = Typograph::replaceAll($otherFields); }
	public function getCreatedBy() { return $this->createdBy; }
	public function setCreatedBy($createdBy) { $this->createdBy = $createdBy; }
	public function getCreatedAt() { return $this->createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }
	public function getRevisions() { return $this->revisions; }
	public function setRevisions($revisions) { $this->revisions = $revisions; }

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
			$this->createAndAddCover($image, BookCoverType::VALUE_FRONT);
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
			$this->createAndAddCover($image, BookCoverType::VALUE_BACK);
		}
	}
	public function getBackCoverFile() { return $this->backCoverFile; }
	public function setBackCover($backCover) {
		// TODO make it smarter
		$this->backCover = str_replace('.tif', '.jpg', $backCover);
	}
	public function getBackCover() { return $this->backCover; }

	private function createAndAddCover(File $image, $type) {
		$this->addCover($this->createCover($image, $type));
		$this->setUpdatedAtOnFileUpload($image);
	}

	/** @return BookCover[] */
	public function getCovers() { return $this->covers; }
	/** @param BookCover[] $covers */
	public function setCovers($covers) { $this->covers = $covers; $this->updateNbCovers(); }

	public function hasOtherCovers() {
		return count($this->getOtherCovers()) > 0;
	}

	/**
	 * @return BookCoverCollection|BookCover[]
	 */
	public function getOtherCovers() {
		$specialCoverNames = [$this->getCover(), $this->getBackCover()];
		return BookCoverCollection::fromCollection($this->getCovers())->filter(function(BookCover $cover) use ($specialCoverNames) {
			return !in_array($cover->getName(), $specialCoverNames);
		});
	}

	/** @param BookCoverCollection|BookCover[] $covers */
	public function setOtherCovers($covers) {
		$covers = BookCoverCollection::fromCollection($covers);
		$covers->onlyNew()->forEach(function(BookCover $cover) {
			$cover->setBook($this);
			$this->covers[] = $cover;
		});
		$oldCoversToKeep = $covers->notNew();
		$this->getOtherCovers()->forEach(function(BookCover $otherCover) use ($oldCoversToKeep) {
			if (!$otherCover->isNew() && !$oldCoversToKeep->contains($otherCover)) {
				$this->removeCover($otherCover);
			}
		});
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
			$cover->setType(new BookCoverType($type));
			$cover->setInternalFormat($image->guessExtension());
			$cover->setTitle($title);
		}
		return $cover;
	}

	/** @return BookScan[]|BookScanCollection */
	public function getScans() {
		return BookScanCollection::fromCollection($this->scans)->sortByTitle();
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
		$setCreatedBy = function (BookFile $file) use ($user) {
			if ($file->isNew()) {
				$file->setCreatedBy($user);
			}
		};
		EntityCollection::forEachIn($this->scans, $setCreatedBy);
		EntityCollection::forEachIn($this->covers, $setCreatedBy);
		EntityCollection::forEachIn($this->newCovers, $setCreatedBy);
	}

	protected function setUpdatedAtOnFileUpload($image) {
		if ($image && $image instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
			$this->updatedAt = new \DateTime();
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
		$this->updateNbCovers();
		$this->updateNbScans();
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate() {
		if ($this->updatedTrackingEnabled) {
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
		$diffs = (new Editor())->computeBookDifferences($oldBook, $this);
		if (empty($diffs) || !$this->shouldCreateRevision($user)) {
			return null;
		}
		$revision = $this->createRevision();
		$revision->setDiffs($diffs);
		$revision->setCreatedBy($user);
		return $revision;
	}

	private function shouldCreateRevision($user) {
		return $user != $this->getCreatedBy() || $this->hasRevisions() || ((time() - $this->getUpdatedAt()->getTimestamp()) > self::ALLOWED_EDIT_TIME_WO_REVISION);
	}

	public function __toString() {
		return $this->getTitle();
	}

	public function toArray() {
		return $this->authorshipToArray() +
			$this->bodyToArray() +
			$this->classificationToArray() +
			$this->contentToArray() +
			$this->groupingToArray() +
			$this->metaToArray() +
			$this->printToArray() +
			$this->publishingToArray() +
			$this->staffToArray() +
			$this->titlingToArray() + [
			'otherFields' => $this->otherFields,
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'otherCovers' => $this->getOtherCovers()->toArray(),
			'scans' => $this->getScans()->toArray(),
			'nbScans' => $this->nbScans,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->getCreatedAt(),
			'updatedAt' => $this->getUpdatedAt(),
		];
	}

	public function __clone() {
		$this->scans = clone $this->scans;
	}

}
