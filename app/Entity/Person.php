<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PersonRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="name_idx", columns={"name"})}
 * )
 */
class Person extends Entity {

	/**
	 * @var string $name
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	private $nameType;

	/**
	 * @var Person
	 * @ORM\ManyToOne(targetEntity="Person")
	 */
	private $canonicalPerson;

	public function __toString() {
		return $this->name;
	}

	public function toArray() {
		return [
			'name' => $this->name,
			'nameType' => $this->nameType,
		];
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
		$this->slug = $name;
	}

	/**
	 * @return string
	 */
	public function getNameType() {
		return $this->nameType;
	}

	/**
	 * @param string $nameType
	 */
	public function setNameType($nameType) {
		$this->nameType = $nameType;
	}

	/**
	 * @return Person
	 */
	public function getCanonicalPerson() {
		return $this->canonicalPerson;
	}

	/**
	 * @param Person $canonicalPerson
	 */
	public function setCanonicalPerson($canonicalPerson) {
		$this->canonicalPerson = $canonicalPerson;
	}
}
