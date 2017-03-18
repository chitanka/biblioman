<?php namespace App\Entity;

use App\Collection\BookCoverCollection;
use App\Collection\BookScanCollection;
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

	use BookComponents { BookComponents::toArray as private componentsToArray; }
	use BookLinks;
	use BookRevisions { BookRevisions::toArray as private revisionsToArray; }
	use BookShelves;
	use CanBeLocked;
	use HasTimestamp { HasTimestamp::toArray as private timestampToArray; }

	private $updatedTrackingEnabled = true;

//	/**
//	 * @ORM\OneToMany(targetEntity="BookItem", mappedBy="book")
//	 * @ORM\OrderBy({"position" = "ASC"})
//	 */
//	private $items;

	public function __construct() {
		$this->covers = new BookCoverCollection();
		$this->newCovers = new BookCoverCollection();
		$this->scans = new BookScanCollection();
		$this->links = new ArrayCollection();
		$this->revisions = new ArrayCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
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

	public function __toString() {
		return $this->title;
	}

	public function __call($name, $args) {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		$normalizedName = lcfirst(preg_replace('/^get/', '', $name));
		if (property_exists($this, $normalizedName)) {
			return $this->$normalizedName;
		}
		return null;
	}

	public function toArray() {
		return $this->componentsToArray() +
			$this->revisionsToArray() +
			$this->timestampToArray();
	}

	public function __clone() {
		$this->scans = clone $this->scans;
	}
}
