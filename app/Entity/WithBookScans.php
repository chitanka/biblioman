<?php namespace App\Entity;

use App\Collection\BookScans;
use Doctrine\ORM\Mapping as ORM;

trait WithBookScans {

	/**
	 * @var BookScan[]|BookScans
	 * @ORM\OneToMany(targetEntity="BookScan", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	private $scans;

	/**
	 * Number of uploaded scans for the book
	 * @ORM\Column(type="smallint")
	 */
	private $nbScans;

	/** @return BookScan[]|BookScans */
	public function getScans() {
		return BookScans::fromCollection($this->scans)->sortByTitle();
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
