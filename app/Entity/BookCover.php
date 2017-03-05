<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookCover extends BookFile {

	const TYPE_FRONT = 'front';
	const TYPE_BACK = 'back';
	const TYPE_INNER = 'inner';
	const TYPE_OTHER = 'other';

	public static $types = [
		self::TYPE_FRONT,
		self::TYPE_BACK,
		self::TYPE_INNER,
		self::TYPE_OTHER,
	];

	/** @ORM\ManyToOne(targetEntity="Book", inversedBy="covers") */
	protected $book;

	/** @Vich\UploadableField(mapping="cover", fileNameProperty="name") */
	protected $file;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=10)
	 */
	protected $type;

	public function __toString() {
		return $this->getType();
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
