<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookGrouping {

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $sequence;

	/**
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $sequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $subsequence;

	/**
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $subsequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $series;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	private $seriesNr;

	public function setSequence($sequence) { $this->sequence = Typograph::replaceAll($sequence); }
	public function setSequenceNr($sequenceNr) { $this->sequenceNr = $sequenceNr; }
	public function setSubsequence($subsequence) { $this->subsequence = Typograph::replaceAll($subsequence); }
	public function setSubsequenceNr($subsequenceNr) { $this->subsequenceNr = $subsequenceNr; }
	public function setSeries($series) { $this->series = Typograph::replaceAll($series); }
	public function setSeriesNr($seriesNr) { $this->seriesNr = $seriesNr; }

	protected function groupingToArray() {
		return [
			'sequence' => $this->sequence,
			'sequenceNr' => $this->sequenceNr,
			'subsequence' => $this->subsequence,
			'subsequenceNr' => $this->subsequenceNr,
			'series' => $this->series,
			'seriesNr' => $this->seriesNr,
		];
	}

}
