<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookPublishing {

	/**
	 * Поредност на изданието
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $edition;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $publisher;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $publisherCity;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $publishingYear;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $publisherAddress;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $publisherCode;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $publisherOrder;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $publisherNumber;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $price;

	public function setEdition($edition) { $this->edition = $edition; }
	public function setPublisher($publisher) { $this->publisher = Typograph::replaceAll($publisher); }
	public function setPublisherCity($publisherCity) { $this->publisherCity = $publisherCity; }
	public function setPublishingYear($publishingYear) { $this->publishingYear = $publishingYear; }
	public function setPublisherAddress($publisherAddress) { $this->publisherAddress = Typograph::replaceAll($publisherAddress); }
	public function setPublisherCode($publisherCode) { $this->publisherCode = $publisherCode; }
	public function setPublisherOrder($publisherOrder) { $this->publisherOrder = Typograph::replaceAll($publisherOrder); }
	public function setPublisherNumber($publisherNumber) { $this->publisherNumber = $publisherNumber; }
	public function setPrice($price) { $this->price = $price; }

	protected function publishingToArray() {
		return [
			'edition' => $this->edition,
			'publisher' => $this->publisher,
			'publisherCity' => $this->publisherCity,
			'publishingYear' => $this->publishingYear,
			'publisherAddress' => $this->publisherAddress,
			'publisherCode' => $this->publisherCode,
			'publisherOrder' => $this->publisherOrder,
			'publisherNumber' => $this->publisherNumber,
			'price' => $this->price,
		];
	}

}
