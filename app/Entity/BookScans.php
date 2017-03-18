<?php namespace App\Entity;

use App\Collection\BookScanCollection;
use Doctrine\ORM\Mapping as ORM;

trait BookScans {

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

	protected function updateNbScans() {
		$this->nbScans = count($this->scans);
	}

}
