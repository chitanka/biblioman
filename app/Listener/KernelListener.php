<?php namespace App\Listener;

use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class KernelListener implements EventSubscriberInterface {

	public static function getSubscribedEvents() {
		return [
			KernelEvents::REQUEST => 'onKernelRequest',
		];
	}

	private $em;
	private $tokenStorage;
	private $singleLoginProvider;

	public function __construct(EntityManager $em, TokenStorage $tokenStorage, $singleLoginProvider) {
		$this->em = $em;
		$this->tokenStorage = $tokenStorage;
		$this->singleLoginProvider = $singleLoginProvider;
	}

	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event) {
		if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
			return;
		}
		$this->initTokenStorage();
	}

	private function initTokenStorage() {
		if (!$this->singleLoginProvider) {
			return;
		}
		$chitankaUser = (require $this->singleLoginProvider)();
		if ($chitankaUser['username']) {
			$repo = $this->em->getRepository('App:User');
			$user = $repo->findByUsername($chitankaUser['username']);
			if (!$user) {
				$user = $repo->createUser($chitankaUser['username'], $chitankaUser['email']);
			}
			$token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, null, 'User', $user->getRoles());
			$this->tokenStorage->setToken($token);
		}
	}

}
