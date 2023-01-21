<?php namespace App\Entity;

use App\File\Thumbnail;
use App\Php\Looper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

trait WithBookFiles {

	use WithBookCovers;
	use WithBookScans;
	use WithBookContentFiles;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $fullContent;

	/**
	 * @Vich\UploadableField(mapping="fullcontent", fileNameProperty="fullContent")
	 * @var File
	 */
	public $fullContentFile;

	/**
	 * If set, the content file will be available for the public at the given date.
	 * @var \DateTime
	 * @ORM\Column(type="date", nullable=true)
	 */
	public $availableAt;

	/**
	 * Is the full book content available to the general public?
	 * @ORM\Column(type="boolean")
	 */
	public $isPublic = false;

	abstract public function canHaveScans(): bool;

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFullContentFile(File $file = null) {
		$this->fullContentFile = $file;
		if ($file) {
			$this->markAsChanged();
		}
	}
	public function setFullContent($fullContent) { $this->fullContent = $fullContent; }

	public function setAvailableAt(\DateTime $availableAt = null) {
		$this->availableAt = $availableAt;
	}

	public function isAvailable() {
		if ($this->availableAt === null) {
			return true;
		}
		return $this->availableAt < new \DateTime();
	}

	public function hasContentFiles(): bool {
		return $this->fullContent !== null || $this->nbContentFiles > 0;
	}

	protected function updateNbFiles() {
		$this->updateNbCovers();
		$this->updateNbContentFiles();
		if ($this->canHaveScans()) {
			$this->updateNbScans();
		}
	}

	public function setCreator(User $user) {
		$setCreatedBy = function (BookFile $file) use ($user) {
			if ($file->isNew()) {
				$file->setCreatedByUser($user);
			} else if ($file->getFile() !== null) {
				$file->setUpdatedByUser($user);
			}
		};
		Looper::forEachValue($this->scans, $setCreatedBy);
		Looper::forEachValue($this->covers, $setCreatedBy);
		Looper::forEachValue($this->newCovers, $setCreatedBy);
		Looper::forEachValue($this->contentFiles, $setCreatedBy);
	}

	protected function filesToArray() {
		return [
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'otherCovers' => $this->getOtherCovers()->toArray(),
			'scans' => $this->getScans()->toArray(),
			'nbScans' => $this->nbScans,
			'contentFiles' => $this->getContentFiles()->toArray(),

			'urls' => [
				'cover' => Thumbnail::createCoverPath($this->cover, 1000),
				'coverSmall' => Thumbnail::createCoverPath($this->cover, 300),
				'coverMini' => Thumbnail::createCoverPath($this->cover, 150),
				'backCover' => Thumbnail::createCoverPath($this->backCover, 1000),
				'backCoverSmall' => Thumbnail::createCoverPath($this->backCover, 300),
				'backCoverMini' => Thumbnail::createCoverPath($this->backCover, 150),
			],

			'availableAt' => $this->availableAt,
			'isPublic' => $this->isPublic,
		];
	}

	abstract protected function markAsChanged();
}
