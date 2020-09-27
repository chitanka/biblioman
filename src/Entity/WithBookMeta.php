<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookMeta {

	public static $STATE_INCOMPLETE = 'incomplete';
	public static $STATE_VERIFIED = 'verified';

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $otherFields;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $notes;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $infoSources;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $adminComment;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public $ocredText;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	public $hasOnlyScans = false;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	public $isIncomplete = true;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	public $reasonWhyIncomplete;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	public $verifiedCount = 0;

	public function setOtherFields($otherFields) { $this->otherFields = Typograph::replaceAll($otherFields); }
	public function setNotes($notes) { $this->notes = Typograph::replaceAll($notes); }
	public function setInfoSources($infoSources) { $this->infoSources = $infoSources; }
	public function setAdminComment($adminComment) { $this->adminComment = $adminComment; }
	public function setOcredText($ocredText) { $this->ocredText = $ocredText; }
	public function hasOnlyScans() { return $this->hasOnlyScans; }
	public function setHasOnlyScans($hasOnlyScans) { $this->hasOnlyScans = $hasOnlyScans; }
	public function isIncomplete() { return $this->isIncomplete; }
	public function setIsIncomplete($isIncomplete) {
		if ($this->isVerified()) {
			// no way back when verified
			return;
		}
		$this->isIncomplete = $isIncomplete;
	}
	public function setReasonWhyIncomplete($reasonWhyIncomplete) { $this->reasonWhyIncomplete = $reasonWhyIncomplete; }
	public function verify(User $user) {
		if ($this->isIncomplete) {
			$this->setIsIncomplete(false);
		}
		$this->verifiedCount++;
	}
	public function isVerified() { return $this->verifiedCount > 0; }

	public function getState() {
		if ($this->isIncomplete) {
			return self::$STATE_INCOMPLETE;
		}
		return self::$STATE_VERIFIED.'_'.$this->verifiedCount;
	}

	protected function metaToArray() {
		return [
			'otherFields' => $this->otherFields,
			'notes' => $this->notes,
			'infoSources' => $this->infoSources,
			'ocredText' => $this->ocredText,
			'hasOnlyScans' => $this->hasOnlyScans,
			'isIncomplete' => $this->isIncomplete,
			'reasonWhyIncomplete' => $this->reasonWhyIncomplete,
			'verifiedCount' => $this->verifiedCount,
		];
	}

}
