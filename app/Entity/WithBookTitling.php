<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookTitling {

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

	public function setTitle($title) { $this->title = Typograph::replaceAll($title); }
	public function setAltTitle($altTitle) { $this->altTitle = Typograph::replaceAll($altTitle); }
	public function setSubtitle($subtitle) { $this->subtitle = Typograph::replaceAll($subtitle); }
	public function setSubtitle2($subtitle2) { $this->subtitle2 = Typograph::replaceAll($subtitle2); }
	public function setVolumeTitle($volumeTitle) { $this->volumeTitle = Typograph::replaceAll($volumeTitle); }

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
