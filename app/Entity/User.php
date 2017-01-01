<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Entity\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, ParticipantInterface, \JsonSerializable {

	const ROLE_DEFAULT = 'ROLE_USER';

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

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
	 * @ORM\Column(type="object")
	 */
	private $preferences;

	public function __construct($username, $email, array $roles = []) {
		$this->username = $username;
		$this->email = $email;
		$this->roles = $roles;
		$this->setLastLogin();
	}

	public function __toString() {
		return $this->getUsername();
	}

	public function getId() {
		return $this->id;
	}

	public function getUsername() {
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

	/**
	 * @param array $roles
	 */
	public function setRoles($roles) {
		$this->roles = $roles;
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

	public function jsonSerialize() {
		return [
			'id' => $this->getId(),
			'username' => $this->getUsername(),
		];
	}
}
