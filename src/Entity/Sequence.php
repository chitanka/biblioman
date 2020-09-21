<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Sequence extends Entity {

	public function toArray() {
		return [];
	}
}
