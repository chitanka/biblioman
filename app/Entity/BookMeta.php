<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait BookMeta {

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $otherFields;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notes;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $infoSources;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $adminComment;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $ocredText;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	private $isIncomplete = true;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $reasonWhyIncomplete;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $verified;

	public function setOtherFields($otherFields) { $this->otherFields = Typograph::replaceAll($otherFields); }
	public function setNotes($notes) { $this->notes = Typograph::replaceAll($notes); }
	public function setInfoSources($infoSources) { $this->infoSources = $infoSources; }
	public function setAdminComment($adminComment) { $this->adminComment = $adminComment; }
	public function setOcredText($ocredText) { $this->ocredText = $ocredText; }
	public function isIncomplete() { return $this->isIncomplete; }
	public function setIsIncomplete($isIncomplete) { $this->isIncomplete = $isIncomplete; }
	public function setReasonWhyIncomplete($reasonWhyIncomplete) { $this->reasonWhyIncomplete = $reasonWhyIncomplete; }
	public function setVerified($verified) { $this->verified = $verified; }

	public function toArray() {
		return [
			'otherFields' => $this->otherFields,
			'notes' => $this->notes,
			'infoSources' => $this->infoSources,
			'ocredText' => $this->ocredText,
			'isIncomplete' => $this->isIncomplete,
			'reasonWhyIncomplete' => $this->reasonWhyIncomplete,
			'verified' => $this->verified,
		];
	}

}
