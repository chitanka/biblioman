<?php namespace App\Entity;

use App\Collection\BookCoverCollection;
use App\Collection\BookScanCollection;
use Chitanka\Utils\Typograph;
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

	use BookAuthorship { BookAuthorship::toArray as private authorshipToArray; }
	use BookBody { BookBody::toArray as private bodyToArray; }
	use BookClassification { BookClassification::toArray as private classificationToArray; }
	use BookContent { BookContent::toArray as private contentToArray; }
	use BookFiles { BookFiles::toArray as private filesToArray; }
	use BookGrouping { BookGrouping::toArray as private groupingToArray; }
	use BookMeta { BookMeta::toArray as private metaToArray; }
	use BookPrint { BookPrint::toArray as private printToArray; }
	use BookPublishing { BookPublishing::toArray as private publishingToArray; }
	use BookStaff { BookStaff::toArray as private staffToArray; }
	use BookTitling { BookTitling::toArray as private titlingToArray; }
	use BookLinks;
	use BookRevisions { BookRevisions::toArray as private revisionsToArray; }
	use BookShelves;
	use CanBeLocked;
	use HasTimestamp { HasTimestamp::toArray as private timestampToArray; }

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $otherFields;

	private $updatedTrackingEnabled = true;

//	/**
//	 * @ORM\OneToMany(targetEntity="BookItem", mappedBy="book")
//	 * @ORM\OrderBy({"position" = "ASC"})
//	 */
//	private $items;

	public function __construct() {
		$this->revisions = new ArrayCollection();
		$this->links = new ArrayCollection();
		$this->scans = new BookScanCollection();
		$this->covers = new BookCoverCollection();
		$this->newCovers = new BookCoverCollection();
		$this->booksOnShelf = new ArrayCollection();
		$this->updatedAt = new \DateTime();
	}

	public function setOtherFields($otherFields) { $this->otherFields = Typograph::replaceAll($otherFields); }

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
		return $this->titlingToArray() +
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
			$this->revisionsToArray() +
			$this->timestampToArray() + [
			'otherFields' => $this->otherFields,
		];
	}

	public function __clone() {
		$this->scans = clone $this->scans;
	}
}
