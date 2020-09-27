<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait CanBeLocked {

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	public $lockedAt;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $lockedBy;

	public function setLock($user) {
		$this->lockedBy = $user;
		$this->lockedAt = new \DateTime();
	}

	public function clearLock() {
		$this->lockedBy = null;
		$this->lockedAt = null;
	}

	public function extendLock() {
		$this->lockedAt = new \DateTime("+{$this->getLockExpireTime()} seconds");
	}

	public function isLockedForUser($user) {
		return $this->lockedBy !== null && $this->lockedBy !== $user && !$this->isLockExpired();
	}

	public function isLockExpired() {
		return $this->lockedAt === null || (time() - $this->lockedAt->getTimeStamp() > $this->getLockExpireTime());
	}

	public function getLockedBy() {
		return $this->lockedBy;
	}

	protected function getLockExpireTime() {
		return 300; // 5 minutes
	}
}
