<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Serie extends Entity {

	public function toArray() {
		return [];
	}
}
