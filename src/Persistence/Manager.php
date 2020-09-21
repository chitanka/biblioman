<?php namespace App\Persistence;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\Collection;

class Manager {

	private $em;

	public function __construct(Registry $doctrine) {
		$this->em = $doctrine->getManager();
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
