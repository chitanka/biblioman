<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Entity implements \JsonSerializable {

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	public function getId() {
		return $this->id;
	}

	public function equals(Entity $entity) {
		return $this->getId() === $entity->getId();
	}

	public function toArray() {
		return [
			'id' => $this->id,
		];
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->toArray();
	}

}
