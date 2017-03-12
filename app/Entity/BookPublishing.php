<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookPublishing implements \JsonSerializable {

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

	public function getEdition() { return $this->edition; }
	public function setEdition($edition) { $this->edition = $edition; }
	public function getPublisher() { return $this->publisher; }
	public function setPublisher($publisher) { $this->publisher = Typograph::replaceAll($publisher); }
	public function getPublisherCity() { return $this->publisherCity; }
	public function setPublisherCity($publisherCity) { $this->publisherCity = $publisherCity; }
	public function getPublishingYear() { return $this->publishingYear; }
	public function setPublishingYear($publishingYear) { $this->publishingYear = $publishingYear; }
	public function getPublisherAddress() { return $this->publisherAddress; }
	public function setPublisherAddress($publisherAddress) { $this->publisherAddress = Typograph::replaceAll($publisherAddress); }
	public function getPublisherCode() { return $this->publisherCode; }
	public function setPublisherCode($publisherCode) { $this->publisherCode = $publisherCode; }
	public function getPublisherOrder() { return $this->publisherOrder; }
	public function setPublisherOrder($publisherOrder) { $this->publisherOrder = $publisherOrder; }
	public function getPublisherNumber() { return $this->publisherNumber; }
	public function setPublisherNumber($publisherNumber) { $this->publisherNumber = $publisherNumber; }
	public function getPrice() { return $this->price; }
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

	public function jsonSerialize() {
		return $this->toArray();
	}
}
