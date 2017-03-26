<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookBody {

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

	public function setFormat($format) { $this->format = Typograph::replaceTimesChar($format); }
	public function setPageCount($pageCount) { $this->pageCount = $pageCount; }
	public function setBinding($binding) { $this->binding = $binding; }

	protected function bodyToArray() {
		return [
			'format' => $this->format,
			'pageCount' => $this->pageCount,
			'binding' => $this->binding,
		];
	}

}
