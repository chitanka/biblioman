<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Person {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

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

	public function getId() { return $this->id; }

	public function __toString() {
		return $this->name;
	}
}
