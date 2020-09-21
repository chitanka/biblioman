<?php namespace App\Collection;

class Books extends Entities implements \JsonSerializable {

	private $jsonFormatter;

	public function setJsonFormatter($jsonFormatter) {
		$this->jsonFormatter = $jsonFormatter;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return array
	 */
	public function jsonSerialize() {
		if ($this->jsonFormatter && is_callable($this->jsonFormatter)) {
			return $this->collection->map($this->jsonFormatter);
		}
		return $this->collection;
	}
}
