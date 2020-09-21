<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table
 * @Vich\Uploadable
 */
class BookScan extends BookFile {

	/** @ORM\ManyToOne(targetEntity="Book", inversedBy="scans") */
	protected $book;

	/**
	 * @Vich\UploadableField(mapping="scan", fileNameProperty="name")
	 */
	protected $file;

	public function __toString() {
		$title = $this->getTitle();
		if (empty($title)) {
			return '';
		}
		if (is_numeric($title)) {
			return 'Страница '.$title;
		}
		if (preg_match('/^\d+[-и ,]+\d+$/u', $title)) {
			return 'Страници '.$title;
		}
		return $title;
	}

	public function setName($name) {
		// TODO make it smarter
		$this->name = str_replace('.tif', '.png', $name);
	}
}
