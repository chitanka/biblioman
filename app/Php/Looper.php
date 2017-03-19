<?php namespace App\Php;

class Looper {

	/**
	 * Call a callback fo every key and value from a given traversable object.
	 * The callback should have following signature:
	 *     callback(key, value)
	 * @param \Traversable|array $values
	 * @param \Closure|callable $callback
	 */
	public static function forEachKeyValue($values, $callback) {
		foreach ($values as $key => $value) {
			$callback($key, $value);
		}
	}

	/**
	 * Call a callback fo every value from a given traversable object.
	 * The callback should have following signature:
	 *     callback(value)
	 * @param \Traversable|array $values
	 * @param \Closure|callable $callback
	 */
	public static function forEachValue($values, $callback) {
		foreach ($values as $value) {
			$callback($value);
		}
	}
}
