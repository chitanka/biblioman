<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookBody {

	public static $MEDIA_PAPER = 'хартия';
	public static $MEDIA_DIGITAL = 'цифров';
	public static $MEDIA_PAPER_AND_DIGITAL = 'хартия и цифров';

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	public $media = 'хартия';

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $format;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	public $pageCount;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $binding;

	public static function mediaValues(): array {
		return [self::$MEDIA_PAPER, self::$MEDIA_DIGITAL, self::$MEDIA_PAPER_AND_DIGITAL];
	}

	public function setMedia($media) { $this->media = $media; }
	public function setFormat($format) { $this->format = Typograph::replaceTimesChar($format); }
	public function setPageCount($pageCount) { $this->pageCount = $pageCount; }
	public function setBinding($binding) { $this->binding = $binding; }

	public function canHaveScans(): bool {
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
