<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BookItem extends Entity {

	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="items")
	 */
	private $book;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $position;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $level;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $workType;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $titleOrig;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $authorsOrig;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $pageNum;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $notes;

	public function getBook() { return $this->book; }

	public function toArray() {
		return [
			'book' => $this->getBook(),
			'position' => $this->position,
			'level' => $this->level,
			'workType' => $this->workType,
			'title' => $this->title,
			'titleOrig' => $this->titleOrig,
			'authorsOrig' => $this->authorsOrig,
			'pageNum' => $this->pageNum,
			'notes' => $this->notes,
		];
	}
}
