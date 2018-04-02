<?php namespace App\Entity\Repository;

use App\Collection\Persons;
use App\Entity\Person;
use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository {

	/**
	 * @param string $name
	 * @return Persons|Person[]
	 */
	public function findRelatedAndSelfByName($name) {
		$persons = new Persons($this->findBy(['name' => $name]));
		$personsWithCanonicalOnes = $persons->expandWithCanonicalPersons();
		$relatedPersons = $this->findBy(['canonicalPerson' => $personsWithCanonicalOnes->toArray()]);
		return $personsWithCanonicalOnes->mergeWith($relatedPersons)->unique();
	}

}
