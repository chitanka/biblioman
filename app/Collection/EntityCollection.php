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

	public function forEach(\Closure $c) {
		self::forEachIn($this, $c);
	}

	/**
	 * @param \Traversable|ArrayCollection|array $collection
	 * @param \Closure $c
	 */
	public static function forEachIn($collection, \Closure $c) {
		foreach ($collection as $entity) {
			$c($entity);
		}
	}
}
