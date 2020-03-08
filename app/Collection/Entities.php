<?php namespace App\Collection;

use App\Entity\Entity;
use App\Php\Looper;
use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Entities implements Collection {

	/**
	 * @var ArrayCollection
	 */
	protected $collection;

	/**
	 * @param array|\Traversable|Collection|Entity[] $collection
	 */
	public function __construct($collection = []) {
		if ($collection instanceof static) {
			$this->collection = $collection->collection;
		} else if ($collection instanceof Collection) {
			$this->collection = $collection;
		} else {
			if ($collection instanceof \Traversable) {
				$collection = iterator_to_array($collection);
			}
			$this->collection = new ArrayCollection($collection ?: []);
		}
	}

	public function forEach(\Closure $c) {
		Looper::forEachValue($this->collection, $c);
	}

	public function filter(\Closure $c) {
		if ($this->collection->isEmpty()) {
			return $this;
		}
		return new static(array_values(array_filter($this->collection->getValues(), $c)));
	}

	public function unique() {
		return new static($this->toUniqueKeyArray());
	}

	/**
	 * @param array|Collection $elements
	 * @return static
	 */
	public function mergeWith($elements) {
		if ($elements instanceof Collection) {
			$elements = $elements->getValues();
		}
		return new static(array_merge($this->collection->getValues(), $elements));
	}

	/**
	 * Return all entities which are not present in the other entities.
	 * @param Entities $otherEntities
	 * @return static
	 */
	public function diff(Entities $otherEntities) {
		return new static(array_diff_key($this->toUniqueKeyArray(), $otherEntities->toUniqueKeyArray()));
	}

	public function toUniqueKeyArray() {
		return array_combine(array_map(function (Entity $entity) {
			return $entity->getUniqueKey();
		}, $this->collection->toArray()), $this->collection->toArray());
	}

	public function __call($name, $arguments) {
		$callable = [$this->collection, $name];
		if (!is_callable($callable)) {
			throw new \BadMethodCallException(static::class.": Method '$name' is not defined.");
		}
		return call_user_func_array($callable, $arguments);
	}

	public function add($element) {
//		if (!$entity instanceof Entity) {
//			throw new \InvalidArgumentException(__METHOD__.' expects a parameter of the class Entity.');
//		}
//		$this->collection->set($entity->getId(), $entity);
//		return true;
		return $this->collection->add($element);
	}

	public function clear() {
		$this->collection->clear();
	}

	public function isEmpty() {
		return $this->collection->isEmpty();
	}

	public function remove($key) {
		return $this->collection->remove($key);
	}

	public function removeElement($element) {
		return $this->collection->removeElement($element);
	}

	public function containsKey($key) {
		return $this->collection->containsKey($key);
	}

	public function contains($element) {
		return $this->collection->contains($element);
	}

	public function get($key) {
		return $this->collection->get($key);
	}

	public function getKeys() {
		return $this->collection->getKeys();
	}

	public function getValues() {
		return $this->collection->getValues();
	}

	public function set($key, $value) {
		$this->collection->set($key, $value);
	}

	public function toArray() {
		return $this->collection->toArray();
	}

	public function first() {
		return $this->collection->first();
	}

	public function last() {
		return $this->collection->last();
	}

	public function key() {
		return $this->collection->key();
	}

	public function current() {
		return $this->collection->current();
	}

	public function next() {
		return $this->collection->next();
	}

	public function exists(Closure $p) {
		return $this->collection->exists($p);
	}

	public function forAll(Closure $p) {
		return $this->collection->forAll($p);
	}

	public function map(Closure $func) {
		return $this->collection->map($func);
	}

	public function partition(Closure $p) {
		return $this->collection->partition($p);
	}

	public function indexOf($element) {
		return $this->collection->indexOf($element);
	}

	public function slice($offset, $length = null) {
		return $this->collection->slice($offset, $length);
	}

	public function getIterator() {
		return $this->collection->getIterator();
	}

	public function offsetExists($offset) {
		return $this->collection->offsetExists($offset);
	}

	public function offsetGet($offset) {
		return $this->collection->offsetGet($offset);
	}

	public function offsetSet($offset, $value) {
		$this->collection->offsetSet($offset, $value);
	}

	public function offsetUnset($offset) {
		$this->collection->offsetUnset($offset);
	}

	public function count() {
		return $this->collection->count();
	}
}
