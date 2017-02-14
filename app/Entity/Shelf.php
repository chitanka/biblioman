<?php namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\ShelfRepository")
 * @ORM\Table
 */
class Shelf {

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="shelves")
	 */
	private $creator;

	/**
	 * @var BookOnShelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookOnShelf", mappedBy="shelf", fetch="EXTRA_LAZY")
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

	public function __construct($name = null, User $creator = null) {
		$this->booksOnShelf = new ArrayCollection();
		$this->setName($name);
		$this->setCreator($creator);
	}

	public function addBookOnShelf(BookOnShelf $a) {
		$this->booksOnShelf[] = $a;
	}
	public function removeBookOnShelf(BookOnShelf $a) {
		$this->booksOnShelf->removeElement($a);
	}
	public function getBooksOnShelf() {
		return $this->booksOnShelf;
	}

	public function __toString() {
		return $this->getName();
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
	public function getDescription() { return $this->description; }
	public function setDescription($description) { $this->description = $description; }
	public function getCreator() { return $this->creator; }
	public function setCreator(User $creator = null) { $this->creator = $creator; }
	public function getCreatedAt() { return $this->createdAt; }
	public function getUpdatedAt() { return $this->updatedAt; }

}
