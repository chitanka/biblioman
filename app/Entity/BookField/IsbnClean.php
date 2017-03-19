<?php namespace App\Entity\BookField;

class IsbnClean extends BookField {

	public static function normalizeInput($input) {
		return preg_replace('/[^\dX,]/', '', Isbn::normalizeInput($input));
	}
}
