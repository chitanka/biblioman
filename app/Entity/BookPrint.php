<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait BookPrint {

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $printingHouse;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $typeSettingIn;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $printSigned;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $printOut;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $printerSheets;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $publisherSheets;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $provisionPublisherSheets;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $totalPrint;

	public function getPrintingHouse() { return $this->printingHouse; }
	public function setPrintingHouse($printingHouse) { $this->printingHouse = Typograph::replaceAll($printingHouse); }
	public function getTypeSettingIn() { return $this->typeSettingIn; }
	public function setTypeSettingIn($typeSettingIn) { $this->typeSettingIn = $typeSettingIn; }
	public function getPrintSigned() { return $this->printSigned; }
	public function setPrintSigned($printSigned) { $this->printSigned = $printSigned; }
	public function getPrintOut() { return $this->printOut; }
	public function setPrintOut($printOut) { $this->printOut = $printOut; }
	public function getPrinterSheets() { return $this->printerSheets; }
	public function setPrinterSheets($printerSheets) { $this->printerSheets = $printerSheets; }
	public function getPublisherSheets() { return $this->publisherSheets; }
	public function setPublisherSheets($publisherSheets) { $this->publisherSheets = $publisherSheets; }
	public function getProvisionPublisherSheets() { return $this->provisionPublisherSheets; }
	public function setProvisionPublisherSheets($provisionPublisherSheets) { $this->provisionPublisherSheets = $provisionPublisherSheets; }
	public function getTotalPrint() { return $this->totalPrint; }
	public function setTotalPrint($totalPrint) { $this->totalPrint = $totalPrint; }

	public function toArray() {
		return [
			'printingHouse' => $this->printingHouse,
			'typeSettingIn' => $this->typeSettingIn,
			'printSigned' => $this->printSigned,
			'printOut' => $this->printOut,
			'printerSheets' => $this->printerSheets,
			'publisherSheets' => $this->publisherSheets,
			'provisionPublisherSheets' => $this->provisionPublisherSheets,
			'totalPrint' => $this->totalPrint,
		];
	}

}
