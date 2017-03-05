<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookCover extends BookFile {

	/** @ORM\ManyToOne(targetEntity="Book", inversedBy="covers") */
	protected $book;

	/** @Vich\UploadableField(mapping="cover", fileNameProperty="name") */
	protected $file;

	/**
	 * @var BookCoverType
	 * @ORM\Embedded(class = "BookCoverType", columnPrefix = false)
	 */
	protected $type;

	public function __toString() {
		return $this->getType()->__toString();
	}

	public function toArray() {
		return parent::toArray() + [
			'type' => $this->type,
		];
	}

	public function setName($name) {
		// TODO make it smarter
		$this->name = str_replace('.tif', '.jpg', $name);
	}

	public function getType() { return $this->type; }
	public function setType($type) { $this->type = $type; }

}
