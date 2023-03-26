<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 * @ORM\Table(name="label")
 * @UniqueEntity(fields="slug", message="This slug is already in use.")
 * @UniqueEntity(fields="name")
 */
class Label extends Entity {

	const GROUP_GENRE = 'genre';
	const GROUP_CHARACTERISTIC = 'characteristic';

	/**
	 * @ORM\Column(type="string", length=80, unique=true)
	 */
	public string $slug = '';

	/**
	 * @ORM\Column(type="string", length=80, unique=true)
	 */
	public string $name = '';

	/**
	 * @ORM\Column(name="`group`", type="string", length=20)
	 */
	public string $group;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	public string $description = '';

	/**
	 * @ORM\ManyToOne(targetEntity="Label")
	 */
	public ?Label $parent;

	public function __toString() {
		return $this->name;
	}
}
