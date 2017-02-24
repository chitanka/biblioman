<?php namespace App\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class EntityCollection extends ArrayCollection {

	public function toIdArray() {
		$idArray = [];
		foreach ($this as $entity) {
			$idArray[$entity->getId()] = $entity;
		}
		return $idArray;
	}
}
