<?php namespace App\Entity;

use App\Collection\BookCoverCollection;
use App\Collection\BookScanCollection;
use App\Editing\Editor;
use Chitanka\Utils\Typograph;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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

	const ALLOWED_EDIT_TIME_WO_REVISION = 3600; // 1 hour

	use BookAuthorship { toArray as private authorshipToArray; }
	use BookBody { toArray as private bodyToArray; }
	use BookClassification { toArray as private classificationToArray; }
	use BookContent { toArray as private contentToArray; }
	use BookFiles { toArray as private filesToArray; }
	use BookGrouping { toArray as private groupingToArray; }
	use BookMeta { toArray as private metaToArray; }
	use BookPrint { toArray as private printToArray; }
	use BookPublishing { toArray as private publishingToArray; }
	use BookStaff { toArray as private staffToArray; }
	use BookTitling { toArray as private titlingToArray; }
	use CanBeLocked;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $otherFields;

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

	/** @ORM\PrePersist */
	public function onPreInsert() {
		$this->updateNbFiles();
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate() {
		if ($this->updatedTrackingEnabled) {
			$this->updateNbFiles();
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
			$this->filesToArray() +
			$this->groupingToArray() +
			$this->metaToArray() +
			$this->printToArray() +
			$this->publishingToArray() +
			$this->staffToArray() +
			$this->titlingToArray() + [
			'otherFields' => $this->otherFields,
			'createdBy' => $this->createdBy,
			'createdAt' => $this->getCreatedAt(),
			'updatedAt' => $this->getUpdatedAt(),
		];
	}

	public function __clone() {
		$this->scans = clone $this->scans;
	}

}
