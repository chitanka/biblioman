<?php namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\ShelfRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="name_idx", columns={"name"}),
 *     @ORM\Index(name="group_idx", columns={"grouping"})}
 * )
 */
class Shelf extends Entity {

	const DEFAULT_ICON = 'fa-folder-o';

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=40)
	 */
	private $icon = self::DEFAULT_ICON;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @var string
	 * @ORM\Column(name="grouping", type="string", length=60, nullable=true)
	 */
	private $group;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="shelves")
	 */
	private $creator;

	/**
	 * If the shelf is important it will be displayed more prominently than the other shelves.
	 * Each important shelf should get its own add-to-shelf button.
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	private $isImportant = false;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	private $isPublic = false;

	/**
	 * @var BookOnShelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookOnShelf", mappedBy="shelf", cascade={"persist","remove"}, orphanRemoval=true, fetch="EXTRA_LAZY")
	 */
	private $booksOnShelf;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	private $updatedAt;

	/**
	 * Number of books on this shelf
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $nbBooks = 0;

	public function __construct(User $creator = null, $name = null) {
		$this->setCreator($creator);
		$this->setName($name);
		$this->booksOnShelf = new ArrayCollection();
	}

	public function addBookOnShelf(BookOnShelf $a) {
		$this->booksOnShelf[] = $a;
		$this->nbBooks++;
	}
	public function removeBookOnShelf(BookOnShelf $a) {
		$this->booksOnShelf->removeElement($a);
		$this->nbBooks--;
	}
	public function getBooksOnShelf() {
		return $this->booksOnShelf;
	}

	public function addBook(Book $book) {
		$bookOnShelf = new BookOnShelf($book, $this);
		$this->addBookOnShelf($bookOnShelf);
	}

	/**
	 * @param Book|BookOnShelf $book
	 */
	public function removeBook($book) {
		if ($book instanceof BookOnShelf) {
			$this->removeBookOnShelf($book);
			return;
		}
		foreach ($this->getBooksOnShelf() as $bookOnShelf) {
			if ($bookOnShelf->getBook() == $book) {
				$this->removeBookOnShelf($bookOnShelf);
				break;
			}
		}
	}

	public function __toString() {
		return $this->getName();
	}

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
	public function getIcon() { return $this->icon; }
	public function setIcon($icon) { $this->icon = $icon ?: self::DEFAULT_ICON; }
	public function getDescription() { return $this->description; }
	public function setDescription($description) { $this->description = $description; }
	public function getGroup() { return $this->group; }
	public function setGroup($group) { $this->group = $group; }
	public function getCreator() { return $this->creator; }
	public function setCreator(User $creator = null) { $this->creator = $creator; }
	public function isImportant() { return $this->isImportant; }
	public function setIsImportant($isImportant) { $this->isImportant = $isImportant; }
	public function isPublic() { return $this->isPublic; }
	public function setIsPublic($isPublic) { $this->isPublic = $isPublic; }
	public function getCreatedAt() { return $this->createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }
	public function getNbBooks() { return $this->nbBooks; }
	public function setNbBooks($nbBooks) { $this->nbBooks = $nbBooks; }

	public function toArray() {
		return [
			'name' => $this->name,
			'icon' => $this->icon,
			'description' => $this->description,
			'group' => $this->group,
			'creator' => $this->getCreator(),
			'isImportant' => $this->isImportant,
			'isPublic' => $this->isPublic,
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
			'nbBooks' => $this->nbBooks,
		];
	}

}
