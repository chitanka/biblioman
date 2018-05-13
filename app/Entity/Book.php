<?php namespace App\Entity;

use App\Collection\BookCovers;
use App\Collection\BookMultiFields;
use App\Collection\BookScans;
use App\Collection\Entities;
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

	use CanBeLocked;
	use HasEditHistory;
	use HasTimestamp;
	use WithBookComponents;
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
		$this->links = new ArrayCollection();
		$this->revisions = new ArrayCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
		$this->multiFields = new ArrayCollection();
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
		(new BookMultiFields($this->multiFields))->updateFromBook($this);
	}

	/** @ORM\PreUpdate */
	public function onPreUpdate() {
		if ($this->updatedTrackingEnabled) {
			$this->updateNbFiles();
		}
		if ($this->hasOnlyScans) {
			if (empty($this->completedByUser) && $this->currentEditor && !$this->currentEditor->equals($this->createdByUser)) {
				$this->completedByUser = $this->currentEditor;
				$this->completedBy = $this->completedByUser->getName();
			}
			$this->isIncomplete = true;
		}
		(new BookMultiFields($this->multiFields))->updateFromBook($this);
	}

	public function __toString() {
		return $this->title;
	}

	public function __get($name) {
		$normalizedName = lcfirst(preg_replace('/^get/', '', $name));
		if (property_exists($this, $normalizedName)) {
			return $this->$normalizedName;
		}
		return null;
	}

	public function __call($name, $args) {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		return $this->__get($name);
	}

	public function toArray() {
		return parent::toArray() +
			$this->componentsToArray() +
			$this->linksToArray() +
			$this->revisionsToArray() +
			$this->timestampToArray();
	}

	public function isMissingScans() {
		return $this->canHaveScans() && $this->nbScans == 0;
	}

	public function __clone() {
		$this->scans = clone $this->scans;
	}
}
