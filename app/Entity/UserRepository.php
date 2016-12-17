<?php namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface {

	/**
	 * @param string $username
	 * @return User
	 */
	public function findByUsername($username) {
		return $this->findOneBy(['username' => $username]);
	}

	public function loadUserByUsername($username) {
		$user = $this->findByUsername($username);
		if (!$user) {
			throw new UsernameNotFoundException("Unknown username '$username'");
		}
		return $user;
	}

	public function refreshUser(UserInterface $user) {
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}
		return $this->loadUserByUsername($user->getUsername());
	}

	public function supportsClass($class) {
		return User::class === $class;
	}

	public function createUser($username, $email) {
		$user = new User($username, $email);
		$this->_em->persist($user);
		$this->_em->flush($user);
		return $user;
	}
}
