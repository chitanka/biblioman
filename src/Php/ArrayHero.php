<?php namespace App\Php;

class ArrayHero {

	/**
	 * Convert all objects to strings
	 * @param array $data
	 * @return array
	 */
	public static function scalarizeArray($data) {
		foreach ($data as $key => $value) {
			$data[$key] = self::formatValueAsScalar($value);
		}
		return $data;
	}

	private static function formatValueAsScalar($value) {
		if ($value instanceof \DateTime) {
			return $value->format('c');
		}
		if (is_object($value)) {
			return (string) $value;
		}
		if (is_array($value)) {
			return implode(' | ', self::scalarizeArray($value));
		}
		return $value;
	}
}
