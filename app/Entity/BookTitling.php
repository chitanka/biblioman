<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait BookTitling {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $altTitle;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $subtitle;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $subtitle2;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $volumeTitle;

	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = Typograph::replaceAll($title); }
	public function getAltTitle() { return $this->altTitle; }
	public function setAltTitle($altTitle) { $this->altTitle = Typograph::replaceAll($altTitle); }
	public function getSubtitle() { return $this->subtitle; }
	public function setSubtitle($subtitle) { $this->subtitle = Typograph::replaceAll($subtitle); }
	public function getSubtitle2() { return $this->subtitle2; }
	public function setSubtitle2($subtitle2) { $this->subtitle2 = Typograph::replaceAll($subtitle2); }
	public function getVolumeTitle() { return $this->volumeTitle; }
	public function setVolumeTitle($volumeTitle) { $this->volumeTitle = Typograph::replaceAll($volumeTitle); }

	public function toArray() {
		return [
			'title' => $this->title,
			'altTitle' => $this->altTitle,
			'subtitle' => $this->subtitle,
			'subtitle2' => $this->subtitle2,
			'volumeTitle' => $this->volumeTitle,
		];
	}

}
