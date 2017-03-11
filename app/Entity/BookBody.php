<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookBody {

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

	public function getFormat() { return $this->format; }
	public function setFormat($format) { $this->format = Typograph::replaceTimesChar($format); }
	public function getPageCount() { return $this->pageCount; }
	public function setPageCount($pageCount) { $this->pageCount = $pageCount; }
	public function getBinding() { return $this->binding; }
	public function setBinding($binding) { $this->binding = $binding; }

	public function toArray() {
		return [
			'format' => $this->format,
			'pageCount' => $this->pageCount,
			'binding' => $this->binding,
		];
	}

	public function jsonSerialize() {
		return $this->toArray();
	}
}
