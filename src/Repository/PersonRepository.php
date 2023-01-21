<?php namespace App\Repository;

use App\Collection\Persons;
use App\Entity\Person;

class PersonRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, Person::class);
	}

	/**
	 * @param string $name
	 * @return Persons|Person[]
	 */
	public function findRelatedAndSelfByName($name) {
		$persons = new Persons($this->findBy(['name' => $name]));
		if ($persons->isEmpty()) {
			return $persons;
		}
		$personsWithCanonicalOnes = $persons->expandWithCanonicalPersons();
		$relatedPersons = $this->findBy(['canonicalPerson' => $personsWithCanonicalOnes->toArray()]);
		return $personsWithCanonicalOnes->mergeWith($relatedPersons)->unique();
	}

}
