<?php namespace App\Php;

class ArrayHero {

	/**
	 * Convert all objects to strings
	 * @param array $data
	 * @return array
	 */
	public static function scalarizeArray($data) {
		foreach ($data as $key => $value) {
			if ($value instanceof \DateTime) {
				$data[$key] = $value->format('c');
			} else if (is_object($value) && method_exists($value, '__toString')) {
				$data[$key] = $value->__toString();
			} else if (is_array($value)) {
				$data[$key] = self::scalarizeArray($value);
			}
		}
		return $data;
	}

}
