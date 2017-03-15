<?php namespace App\Entity;

use App\Collection\BookCoverCollection;
use App\Collection\BookScanCollection;
use App\Collection\EntityCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

trait BookFiles {

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

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFullContentFile(File $file = null) { $this->fullContentFile = $file; $this->setUpdatedAtOnFileUpload($file); }
	public function setFullContent($fullContent) { $this->fullContent = $fullContent; }

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image */
	public function setCoverFile(File $image = null) {
		$this->coverFile = $image;
		if ($image !== null) {
			$this->createAndAddCover($image, BookCoverType::VALUE_FRONT);
		}
	}
	public function setCover($cover) {
		// TODO make it smarter
		$this->cover = str_replace('.tif', '.jpg', $cover);
	}

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image */
	public function setBackCoverFile(File $image = null) {
		$this->backCoverFile = $image;
		if ($image !== null) {
			$this->createAndAddCover($image, BookCoverType::VALUE_BACK);
		}
	}
	public function setBackCover($backCover) {
		// TODO make it smarter
		$this->backCover = str_replace('.tif', '.jpg', $backCover);
	}

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
		$specialCoverNames = [$this->cover, $this->backCover];
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

	public function setNbScans($nbScans) { $this->nbScans = $nbScans; }

	protected function updateNbScans() {
		$this->setNbScans(count($this->scans));
	}

	protected function updateNbFiles() {
		$this->updateNbCovers();
		$this->updateNbScans();
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
			$this->markAsChanged();
		}
	}

	public function toArray() {
		return [
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'otherCovers' => $this->getOtherCovers()->toArray(),
			'scans' => $this->getScans()->toArray(),
			'nbScans' => $this->nbScans,
		];
	}

	abstract protected function markAsChanged();
}
