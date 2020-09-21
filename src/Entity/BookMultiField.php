<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={
 *     @ORM\Index(name="field_idx", columns={"field"}),
 *     @ORM\Index(name="value_idx", columns={"value"})}
 * )
 */
class BookMultiField extends Entity {

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="multiFields")
	 */
	private $book;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=40)
	 */
	private $field;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	private $value;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @param Book $book
	 * @param string $field
	 * @param string $value
	 */
	public function __construct(Book $book, string $field, string $value) {
		$this->book = $book;
		$this->field = $field;
		$this->value = $value;
		$this->hash = $this->createHash();
	}

	/**
	 * @return string
	 */
	public function getUniqueKey() {
		return $this->hash ?: $this->createHash();
	}

	protected function createHash() {
		return $this->hash = $this->book->getId().':'.$this->field.':'.$this->value;
	}

	public function __toString() {
		return $this->hash;
	}

	public function remove() {
		$this->book = null;
	}
}
