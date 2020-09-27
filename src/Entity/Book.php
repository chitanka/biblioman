<?php namespace App\Entity;

use App\Collection\BookMultiFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Book extends Entity {

	use CanBeLocked;
	use HasEditHistory;
	use HasTimestamp;
	use WithBookAuthorship;
	use WithBookBody;
	use WithBookClassification;
	use WithBookContent;
	use WithBookFiles;
	use WithBookGrouping;
	use WithBookMeta;
	use WithBookPrint;
	use WithBookPublishing;
	use WithBookStaff;
	use WithBookTitling;
	use WithBookLinks;
	use WithBookShelves;

	private $updatedTrackingEnabled = true;

	/**
	 * @var BookMultiField[]|BookMultiFields
	 * @ORM\OneToMany(targetEntity="BookMultiField", mappedBy="book", cascade={"persist"}, orphanRemoval=true)
	 */
	private $multiFields;

//	/**
//	 * @ORM\OneToMany(targetEntity="BookItem", mappedBy="book")
//	 * @ORM\OrderBy({"position" = "ASC"})
//	 */
//	private $items;

	public function __construct() {
		$this->covers = new ArrayCollection();
		$this->newCovers = new ArrayCollection();
		$this->scans = new ArrayCollection();
		$this->contentFiles = new ArrayCollection();
		$this->links = new ArrayCollection();
		$this->revisions = new ArrayCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
		$this->multiFields = new ArrayCollection();
	}

	public function disableUpdatedTracking() {
		$this->updatedTrackingEnabled = false;
	}

	/** @ORM\PrePersist */
	public function onPreInsert() {
		$this->updateNbFiles();
		(new BookMultiFields($this->multiFields))->updateFromBook($this);
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate(PreUpdateEventArgs $event) {
		if (!$this->updatedTrackingEnabled) {
			return;
		}
		$this->updateNbFiles();
		if ($this->hasOnlyScans || $event->hasChangedField('hasOnlyScans')) {
			if (empty($this->completedByUser) && $this->currentEditor && !$this->currentEditor->equals($this->createdByUser)) {
				$this->completedByUser = $this->currentEditor;
				$this->completedBy = $this->completedByUser->getName();
			}
		}
		if ($this->hasOnlyScans) {
			$this->isIncomplete = true;
		}
		(new BookMultiFields($this->multiFields))->updateFromBook($this);
	}

	public function __toString() {
		return $this->title;
	}

	public function toArray() {
		return parent::toArray() +
			$this->titlingToArray() +
			$this->authorshipToArray() +
			$this->bodyToArray() +
			$this->classificationToArray() +
			$this->contentToArray() +
			$this->filesToArray() +
			$this->groupingToArray() +
			$this->metaToArray() +
			$this->printToArray() +
			$this->publishingToArray() +
			$this->staffToArray() +
			$this->linksToArray() +
			$this->revisionsToArray() +
			$this->timestampToArray();
	}

	public function isMissingScans() {
		return $this->canHaveScans() && $this->nbScans == 0;
	}

	public function __clone() {
		$this->scans = clone $this->scans;
		$this->contentFiles = clone $this->contentFiles;
	}
}
