<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Person extends Entity {

	/**
	 * @var string $slug
	 * @ORM\Column(type="string", length=50, unique=true)
	 */
	private $slug;

	/**
	 * @var string $name
	 * @ORM\Column(type="string", length=100)
	 */
	private $name = '';

	public function __toString() {
		return $this->name;
	}

	public function toArray() {
		return [
			'name' => $this->name,
			'slug' => $this->slug,
		];
	}
}
