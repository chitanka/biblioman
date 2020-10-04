<?php namespace App\Entity;

use App\Collection\Shelves;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait WithBookShelves {

	/**
	 * @var BookOnShelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookOnShelf", mappedBy="book", fetch="EXTRA_LAZY")
	 */
	public $booksOnShelf;

	/**
	 * @var Shelf[]|ArrayCollection
	 */
	public $shelves;

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

	private $publicShelves;
	public function getPublicShelves() {
		return $this->publicShelves ?: $this->publicShelves = $this->getShelves()->filter(function (Shelf $shelf) {
			return $shelf->isPublic();
		});
	}

	public function hasPublicShelves() {
		return !$this->getPublicShelves()->isEmpty();
	}
}
