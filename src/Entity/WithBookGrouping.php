<?php namespace App\Entity;

use Chitanka\Utils\Typograph;
use Doctrine\ORM\Mapping as ORM;

trait WithBookGrouping {

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $sequence;

	/**
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	public $sequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $subsequence;

	/**
	 * @var int
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	public $subsequenceNr;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	public $series;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	public $seriesNr;

	public function setSequence($sequence) { $this->sequence = Typograph::replaceAll($sequence); }
	public function setSequenceNr($sequenceNr) { $this->sequenceNr = $sequenceNr; }
	public function setSubsequence($subsequence) { $this->subsequence = Typograph::replaceAll($subsequence); }
	public function setSubsequenceNr($subsequenceNr) { $this->subsequenceNr = $subsequenceNr; }
	public function setSeries($series) { $this->series = Typograph::replaceAll($series); }
	public function setSeriesNr($seriesNr) { $this->seriesNr = $seriesNr; }

	public function isSeriesSameAsTheSequence() {
		return $this->series === $this->sequence && $this->seriesNr == $this->sequenceNr;
	}

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
