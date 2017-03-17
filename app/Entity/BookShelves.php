<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait BookShelves {

	/**
	 * @var BookOnShelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookOnShelf", mappedBy="book", fetch="EXTRA_LAZY")
	 */
	private $booksOnShelf;

	/**
	 * @var Shelf[]|ArrayCollection
	 */
	private $shelves;

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

}
