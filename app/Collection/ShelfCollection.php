<?php namespace App\Collection;

class ShelfCollection extends EntityCollection {

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
