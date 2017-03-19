<?php namespace App\Php;

class Looper {

	/**
	 * @param array|\Traversable $values
	 * @param callable $callback
	 */
	public static function doWithEveryKeyValue($values, $callback) {
		foreach ($values as $key => $value) {
			$callback($key, $value);
		}
	}
}
