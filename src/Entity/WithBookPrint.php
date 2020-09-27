<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookPrint {

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $printingHouse;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $typeSettingIn;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $printSigned;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $printOut;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $printerSheets;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $publisherSheets;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $provisionPublisherSheets;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	public $totalPrint;

	public function setPrintingHouse($printingHouse) { $this->printingHouse = Typograph::replaceAll($printingHouse); }
	public function setTypeSettingIn($typeSettingIn) { $this->typeSettingIn = $typeSettingIn; }
	public function setPrintSigned($printSigned) { $this->printSigned = $printSigned; }
	public function setPrintOut($printOut) { $this->printOut = $printOut; }
	public function setPrinterSheets($printerSheets) { $this->printerSheets = $printerSheets; }
	public function setPublisherSheets($publisherSheets) { $this->publisherSheets = $publisherSheets; }
	public function setProvisionPublisherSheets($provisionPublisherSheets) { $this->provisionPublisherSheets = $provisionPublisherSheets; }
	public function setTotalPrint($totalPrint) { $this->totalPrint = $totalPrint; }

	protected function printToArray() {
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
