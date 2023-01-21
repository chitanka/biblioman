<?php namespace App\Repository;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository implements UserProviderInterface {

	public function __construct(\Doctrine\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, User::class);
	}

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

	public function findUsersWithRole(?string $role) {
		if (empty($role)) {
			return $this->findUsersWithExtraRoles();
		}
		$qb = $this->createQueryBuilder('u')
			->where('u.roles LIKE ?1')->setParameter('1', '%'.User::normalizeRoleName($role).'%')
			->orderBy('u.username');
		return $qb->getQuery()->getResult();
	}

	public function findUsersWithExtraRoles() {
		$qb = $this->createQueryBuilder('u')
			->where('u.roles != ?1')->setParameter('1', serialize([]))
			->orderBy('u.username');
		return $qb->getQuery()->getResult();
	}
}
