<?php namespace App\Entity;

use App\Collection\BookCovers;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

trait WithBookCovers {

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
	 * @var BookCover[]|BookCovers
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
	 * @var BookCovers|array
	 */
	private $newCovers = [];

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
		$this->markAsChanged();
	}

	/** @return BookCover[] */
	public function getCovers() { return $this->covers; }
	/** @param BookCover[] $covers */
	public function setCovers($covers) { $this->covers = $covers; $this->updateNbCovers(); }

	public function hasOtherCovers() {
		return count($this->getOtherCovers()) > 0;
	}

	/**
	 * Return all covers except the front and the back one
	 * @return BookCovers|BookCover[]
	 */
	public function getOtherCovers() {
		$specialCoverNames = [$this->cover, $this->backCover];
		return BookCovers::fromCollection($this->covers)->filter(function(BookCover $cover) use ($specialCoverNames) {
			return !in_array($cover->getName(), $specialCoverNames);
		});
	}

	/** @param BookCovers|BookCover[] $covers */
	public function setOtherCovers($covers) {
		$covers = BookCovers::fromCollection($covers);
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

	protected function updateNbCovers() {
		$this->nbCovers = count($this->covers);
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

	abstract protected function markAsChanged();
}
