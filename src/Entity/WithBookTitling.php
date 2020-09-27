<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookTitling {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	public $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $altTitle;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	public $subtitle;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $subtitle2;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $volumeTitle;

	public function setTitle($title) { $this->title = Typograph::replaceAll($title); }
	public function setAltTitle($altTitle) { $this->altTitle = Typograph::replaceAll($altTitle); }
	public function setSubtitle($subtitle) { $this->subtitle = Typograph::replaceAll($subtitle); }
	public function setSubtitle2($subtitle2) { $this->subtitle2 = Typograph::replaceAll($subtitle2); }
	public function setVolumeTitle($volumeTitle) { $this->volumeTitle = Typograph::replaceAll($volumeTitle); }

	public function getTitleWithVolume() {
		if (empty($this->volumeTitle)) {
			return $this->title;
		}
		return "{$this->title} ({$this->volumeTitle})";
	}

	protected function titlingToArray() {
		return [
			'title' => $this->title,
			'altTitle' => $this->altTitle,
			'subtitle' => $this->subtitle,
			'subtitle2' => $this->subtitle2,
			'volumeTitle' => $this->volumeTitle,
		];
	}

}
