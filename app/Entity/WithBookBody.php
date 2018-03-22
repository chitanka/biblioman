<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookBody {

	public static $MEDIA_PAPER = 'paper';
	public static $MEDIA_DIGITAL = 'digital';

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	private $media = 'paper';

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $format;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $pageCount;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $binding;

	public function setMedia($media) { $this->media = $media; }
	public function setFormat($format) { $this->format = Typograph::replaceTimesChar($format); }
	public function setPageCount($pageCount) { $this->pageCount = $pageCount; }
	public function setBinding($binding) { $this->binding = $binding; }

	public function canHaveScans() {
		return $this->media == self::$MEDIA_PAPER;
	}

	protected function bodyToArray() {
		return [
			'media' => $this->media,
			'format' => $this->format,
			'pageCount' => $this->pageCount,
			'binding' => $this->binding,
		];
	}

}
