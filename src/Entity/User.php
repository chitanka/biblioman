<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends Entity implements UserInterface, ParticipantInterface {

	const ROLE_PREFIX = 'ROLE_';

	const ROLE_DEFAULT = 'ROLE_USER';
	const ROLE_WIKI_EDITOR = 'ROLE_WIKI_EDITOR';
	const ROLE_EDITOR = 'ROLE_EDITOR';
	const ROLE_EDITOR_SENIOR = 'ROLE_EDITOR_SENIOR';
	const ROLE_EDITOR_MANAGING = 'ROLE_EDITOR_MANAGING';
	const ROLE_EDITOR_CHIEF = 'ROLE_EDITOR_CHIEF';
	const ROLE_ADMIN = 'ROLE_ADMIN';

	const ROLES = [
		self::ROLE_WIKI_EDITOR,
		self::ROLE_EDITOR,
		self::ROLE_EDITOR_SENIOR,
		self::ROLE_EDITOR_MANAGING,
		self::ROLE_EDITOR_CHIEF,
		self::ROLE_ADMIN,
	];

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, unique=true)
	 */
	private $username;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $email;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $lastLogin;

	/**
	 * @var array
	 * @ORM\Column(type="array")
	 */
	private $roles;

	/**
	 * @var array
	 * @ORM\Column(type="object", nullable=true)
	 */
	private $preferences;

	/**
	 * @var Shelf[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="Shelf", mappedBy="creator", cascade={"persist","remove"}, orphanRemoval=true)
	 */
	private $shelves;

	public static function createAnonymousUser() {
		return new static(null, null);
	}

	public static function normalizeRoleName($roleInput) {
		$role = strtoupper($roleInput);
		if (strpos($role, self::ROLE_PREFIX) === false) {
			$role = self::ROLE_PREFIX.$role;
		}
		return $role;
	}

	public function __construct($username, $email, array $roles = []) {
		$this->username = $username;
		$this->email = $email;
		$this->roles = $roles;
		$this->shelves = new ArrayCollection();
		$this->setLastLogin();
	}

	public function __toString() {
		return (string) $this->getUsername();
	}

	public function isAnonymous() {
		return $this->username === null;
	}

	public function isRegistered() {
		return !$this->isAnonymous();
	}

	public function getUsername() {
		return $this->username;
	}

	public function getName() {
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastLogin() {
		return $this->lastLogin;
	}

	/**
	 * @param \DateTime $time
	 */
	public function setLastLogin(\DateTime $time = null) {
		$this->lastLogin = $time ?: new \DateTime();
	}

	/**
	 * @return array
	 */
	public function getRoles() {
		return array_merge([self::ROLE_DEFAULT], $this->roles);
	}

	public function getNonDefaultRoles() {
		return $this->roles;
	}

	/**
	 * @param array $roles
	 */
	public function setRoles($roles) {
		$this->roles = $roles;
	}

	public function addRole($role) {
		if (!in_array($role, $this->roles)) {
			$this->roles[] = $role;
		}
	}

	public function removeRole($role) {
		$this->roles = array_diff($this->roles, [$role]);
	}

	public function is($role) {
		return $this->hasRole($role) || $this->hasRole(self::ROLE_ADMIN);
	}

	public function hasRole(string $role): bool {
		return in_array(self::normalizeRoleName($role), $this->getRoles());
	}

	/**
	 * @return array
	 */
	public function getPreferences() {
		return $this->preferences;
	}

	public function getPreference($name, $default = null) {
		if (isset($this->preferences[$name])) {
			return $this->preferences[$name];
		}
		return $default;
	}

	/**
	 * @param array $preferences
	 */
	public function setPreferences($preferences) {
		$this->preferences = $preferences;
	}

	public function setPreference($name, $value) {
		$this->preferences[$name] = $value;
	}

	public function getPassword() {
		return null;
	}

	public function getSalt() {
		return null;
	}

	public function eraseCredentials() {}

	public function toArray() {
		return [
			'username' => $this->getUsername(),
		];
	}

	public function canAccessBookContents(Book $book): bool {
		if ($book->isPublic) {
			return true;
		}
		return $this->isRegistered() && ($book->isAvailable() || $book->isCreatedByTheUser($this) || $this->is(self::ROLE_EDITOR_SENIOR));
	}

	public function canEditBook(Book $book): bool {
		if (!$this->is(self::ROLE_EDITOR)) {
			return false;
		}
		if ($book->isVerified() && !$this->is(self::ROLE_EDITOR_CHIEF)) {
			return false;
		}
		return $book->isIncomplete
			|| $book->createdBy == $this->getUsername()
			|| $book->completedBy == $this->getUsername()
			|| $this->is(self::ROLE_EDITOR_SENIOR);
	}

	public function canVerifyBook(Book $book) {
		return $this->is(self::ROLE_EDITOR_MANAGING);
	}
}
