<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Entity {

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	public function getId() { return $this->id; }
}
