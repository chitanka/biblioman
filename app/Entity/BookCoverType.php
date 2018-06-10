<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class BookCoverType {

	const VALUE_FRONT = 'front';
	const VALUE_BACK = 'back';
	const VALUE_INNER = 'inner';
	const VALUE_SIDE = 'side';
	const VALUE_OTHER = 'other';

	public static $values = [
		self::VALUE_FRONT,
		self::VALUE_BACK,
		self::VALUE_INNER,
		self::VALUE_SIDE,
		self::VALUE_OTHER,
	];

	/**
	 * @var string
	 * @ORM\Column(name="type", type="string", length=10)
	 */
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function __toString() {
		return $this->value;
	}

	public static function getValuesAsAssocArray() {
		return array_combine(self::$values, self::$values);
	}
}
