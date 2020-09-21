<?php namespace App\Php;

class RegExp {

	public static function gluePrefixesForRegExp($prefixes) {
		return implode('|', array_map('preg_quote', $prefixes));
	}

}
