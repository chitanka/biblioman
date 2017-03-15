<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait BookPublishing {

	/**
	 * Поредност на изданието
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $edition;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $publisher;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publisherCity;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $publishingYear;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $publisherAddress;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherCode;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherOrder;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $publisherNumber;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	private $price;

	public function setEdition($edition) { $this->edition = $edition; }
	public function setPublisher($publisher) { $this->publisher = Typograph::replaceAll($publisher); }
	public function setPublisherCity($publisherCity) { $this->publisherCity = $publisherCity; }
	public function setPublishingYear($publishingYear) { $this->publishingYear = $publishingYear; }
	public function setPublisherAddress($publisherAddress) { $this->publisherAddress = Typograph::replaceAll($publisherAddress); }
	public function setPublisherCode($publisherCode) { $this->publisherCode = $publisherCode; }
	public function setPublisherOrder($publisherOrder) { $this->publisherOrder = $publisherOrder; }
	public function setPublisherNumber($publisherNumber) { $this->publisherNumber = $publisherNumber; }
	public function setPrice($price) { $this->price = $price; }

	public function toArray() {
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
