<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait HasTimestamp {

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	public $createdAt;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	public $updatedAt;

	protected function isOlderThanSeconds($seconds) {
		return (time() - $this->createdAt->getTimestamp()) > $seconds;
	}

	protected function markAsChanged() {
		$this->updatedAt = new \DateTime();
	}

	protected function timestampToArray() {
		return [
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
		];
	}
}
