<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookMeta implements \JsonSerializable {

	public static $inAdminMode = false;

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

	public function getNotes() { return $this->notes; }
	public function setNotes($notes) { $this->notes = Typograph::replaceAll($notes); }
	public function getInfoSources() { return $this->infoSources; }
	public function setInfoSources($infoSources) { $this->infoSources = $infoSources; }
	public function getAdminComment() { return $this->adminComment; }
	public function setAdminComment($adminComment) { $this->adminComment = $adminComment; }
	public function getOcredText() { return $this->ocredText; }
	public function setOcredText($ocredText) { $this->ocredText = $ocredText; }
	public function isIncomplete() { return $this->isIncomplete; }
	public function setIsIncomplete($isIncomplete) { $this->isIncomplete = $isIncomplete; }
	public function getReasonWhyIncomplete() { return $this->reasonWhyIncomplete; }
	public function setReasonWhyIncomplete($reasonWhyIncomplete) { $this->reasonWhyIncomplete = $reasonWhyIncomplete; }
	public function getVerified() { return $this->verified; }
	public function setVerified($verified) { $this->verified = $verified; }

	public function toArray() {
		return [
			'notes' => $this->notes,
			'infoSources' => $this->infoSources,
			'ocredText' => $this->ocredText,
			'isIncomplete' => $this->isIncomplete,
			'reasonWhyIncomplete' => $this->reasonWhyIncomplete,
			'verified' => $this->verified,
		] + (self::$inAdminMode ? ['adminComment' => $this->adminComment] : []);
	}

	public function jsonSerialize() {
		return $this->toArray();
	}
}
