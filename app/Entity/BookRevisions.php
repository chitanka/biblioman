<?php namespace App\Entity;

use App\Editing\Editor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait BookRevisions {

	/**
	 * @var BookRevision[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookRevision", mappedBy="book")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
	 */
	private $revisions;

	public function getRevisions() { return $this->revisions; }
	public function setRevisions($revisions) { $this->revisions = $revisions; }

	public function hasRevisions() {
		return count($this->getRevisions()) > 0;
	}

	public function getRevisionEditors() {
		$editors = [];
		foreach ($this->revisions as $revision) {
			$editors[] = $revision->getCreatedBy();
		}
		return array_unique($editors);
	}

	public function createRevisionIfNecessary(Book $oldBook, $user) {
		$diffs = (new Editor())->computeBookDifferences($oldBook, $this);
		if (empty($diffs) || !$this->shouldCreateRevision($user)) {
			return null;
		}
		$revision = $this->createRevision();
		$revision->setDiffs($diffs);
		$revision->setCreatedBy($user);
		return $revision;
	}

	/** @return BookRevision */
	private function createRevision() {
		$revision = new BookRevision();
		$revision->setBook($this);
		$revision->setCreatedAt(new \DateTime());
		return $revision;
	}

	private function shouldCreateRevision($user) {
		return $user != $this->getCreatedBy() || $this->hasRevisions() || $this->isOlderThanSeconds($this->getAllowedEditTimeWithoutRevision());
	}

	protected function getAllowedEditTimeWithoutRevision() {
		return 3600; // 1 hour
	}

	abstract public function getCreatedBy();
	abstract protected function isOlderThanSeconds($seconds);
}
