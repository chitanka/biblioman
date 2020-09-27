<?php namespace App\Entity;

use App\Editing\Editor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait HasEditHistory {

	/**
	 * @var BookRevision[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookRevision", mappedBy="book")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
	 */
	public $revisions;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	public $createdBy;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	public $createdByUser;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $completedBy;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	public $completedByUser;

	/**
	 * The user who currently edits the entity
	 * @var User
	 */
	public $currentEditor;

	public function getRevisions() {
		return $this->revisions;
	}
	public function setRevisions($revisions) { $this->revisions = $revisions; }
	public function setCreatedByUser(User $user) {
		$this->createdByUser = $user;
		$this->createdBy = $user->getUsername();
	}

	public function isCreatedByTheUser(User $user = null) {
		if ($user === null) {
			return false;
		}
		return $this->createdByUser->equals($user);
	}

	public function setCurrentEditor(User $editor) {
		$this->currentEditor = $editor;
	}

	public function hasRevisions() {
		return count($this->revisions) > 0;
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
		return $user != $this->createdBy || $this->hasRevisions() || $this->isOlderThanSeconds($this->getAllowedEditTimeWithoutRevision());
	}

	protected function getAllowedEditTimeWithoutRevision() {
		return 3600; // 1 hour
	}

	abstract protected function isOlderThanSeconds($seconds);

	protected function revisionsToArray() {
		return [
			'createdBy' => $this->createdBy,
			'completedBy' => $this->completedBy,
		];
	}
}
