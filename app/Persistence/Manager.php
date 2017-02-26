<?php namespace App\Persistence;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

class Manager {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function getRepository($entityName) {
		return $this->em->getRepository($entityName);
	}

	public function save($entities) {
		foreach ($this->asCollection($entities) as $entity) {
			$this->em->persist($entity);
		}
		$this->em->flush();
	}

	public function delete($entities) {
		foreach ($this->asCollection($entities) as $entity) {
			$this->em->remove($entity);
		}
		$this->em->flush();
	}

	private function asCollection($entities) {
		if (!is_array($entities) && !$entities instanceof Collection) {
			return [$entities];
		}
		return $entities;
	}
}
