<?php namespace App\Collection;

class Shelves extends Entities {

	public function toChoices() {
		$choices = [];
		foreach ($this as $shelf) {
			$choices[$shelf->getGroup() ?: ''][] = $shelf;
		}
		$ungroupedChoices = $choices[''];
		unset($choices['']);
		$choices += ['' => $ungroupedChoices];
		return $choices;
	}
}
