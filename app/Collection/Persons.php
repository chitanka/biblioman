<?php namespace App\Collection;

use App\Entity\Person;

class Persons extends Entities {

	public function expandWithCanonicalPersons() {
		return $this->mergeWith($this->canonicalPersons());
	}

	public function names() {
		return $this->map(function (Person $person) {
			return $person->getName();
		})->toArray();
	}

	/**
	 * @return Person[]
	 */
	public function canonicalPersons() {
		$canonicalPersons = [];
		foreach ($this as $person/* @var $person Person */) {
			$canonicalPerson = $person->getCanonicalPerson();
			if ($canonicalPerson) {
				$canonicalPersons[$canonicalPerson->getId()] = $canonicalPerson;
			}
		}
		return $canonicalPersons;
	}
}
