<?php namespace App\Collection;

use App\Entity\Entity;
use App\Php\Looper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Entities extends ArrayCollection {

	public function toIdArray() {
		$idArray = [];
		foreach ($this as $entity/* @var $entity Entity */) {
			$idArray[$entity->getId()] = $entity;
		}
		return $idArray;
	}

	public function forEach(\Closure $c) {
		Looper::forEachValue($this, $c);
	}

	public function filter(\Closure $c) {
		return new static(array_values(array_filter($this->getValues(), $c)));
	}

	public function unique() {
		return new static(array_values($this->toIdArray()));
	}

	/**
	 * @param array|static $elements
	 * @return static
	 */
	public function mergeWith($elements) {
		if ($elements instanceof static) {
			$elements = $elements->getValues();
		}
		return new static(array_merge($this->getValues(), $elements));
	}

	/**
	 * @param static|Collection|array|\Traversable $collection
	 * @return static
	 */
	public static function fromCollection($collection) {
		if ($collection instanceof static) {
			return $collection;
		}
		if ($collection instanceof Collection) {
			return new static($collection->getValues());
		}
		if ($collection instanceof \Traversable) {
			return self::fromTraversable($collection);
		}
		throw new \InvalidArgumentException(sprintf("Cannot create a collection from an argument of type “%s”.", gettype($collection)));
	}

	private static function fromTraversable(\Traversable $collection) {
		$newCollection = new static();
		foreach ($collection as $item) {
			$newCollection[] = $item;
		}
		return $newCollection;
	}
}
