<?php namespace App\Form\DataTransformer;

use App\Entity\Repository\UserRepository;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a User instance and a username string
 */
class UserToUsernameTransformer implements DataTransformerInterface {
	/**
	 * @var UserRepository
	 */
	protected $repository;

	/**
	 * @param Registry $doctrine
	 */
	public function __construct(ManagerRegistry $doctrine) {
		$this->repository = $doctrine->getManager()->getRepository(User::class);
	}

	/**
	 * Transforms a User instance into a username string.
	 *
	 * @param User|null $value User instance
	 *
	 * @return string|null Username
	 *
	 * @throws UnexpectedTypeException if the given value is not a User instance
	 */
	public function transform($value) {
		if (null === $value) {
			return null;
		}

		if (! $value instanceof User) {
			throw new UnexpectedTypeException($value, User::class);
		}

		return $value->getUsername();
	}

	/**
	 * Transforms a username string into a User instance.
	 *
	 * @param string $value Username
	 *
	 * @return User the corresponding User instance
	 *
	 * @throws UnexpectedTypeException if the given value is not a string
	 */
	public function reverseTransform($value) {
		if (null === $value || '' === $value) {
			return null;
		}

		if (! is_string($value)) {
			throw new UnexpectedTypeException($value, 'string');
		}

		return $this->repository->findByUsername($value);
	}
}
