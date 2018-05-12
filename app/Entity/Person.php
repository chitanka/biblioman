<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PersonRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="name_idx", columns={"name"})}
 * )
 */
class Person extends Entity {

	const NAME_TYPE_CANONICAL = 'canonical';
	const NAME_TYPE_REALNAME = 'realname';
	const NAME_TYPE_PSEUDONYM = 'pseudonym';
	const NAME_TYPE_ALTNAME = 'altname';
	const NAME_TYPE_WRONGNAME = 'wrongname';
	const NAME_TYPE_MAYBE = 'maybe';

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
	 * @ORM\ManyToOne(targetEntity="Person", inversedBy="relatedPersons")
	 */
	private $canonicalPerson;

	/**
	 * @var Person[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="Person", mappedBy="canonicalPerson")
	 */
	private $relatedPersons;

	public function __construct() {
		$this->relatedPersons = new ArrayCollection();
	}

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

	/**
	 * @return Person[]
	 */
	public function getRelatedPersons() {
		return $this->relatedPersons;
	}
}
