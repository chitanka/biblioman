<?php namespace App\Listener;

use App\Entity\User;
use App\Persistence\RepositoryFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KernelListener implements EventSubscriberInterface {

	public static function getSubscribedEvents() {
		return [
			KernelEvents::REQUEST => 'onKernelRequest',
			#KernelEvents::EXCEPTION => 'onKernelException',
		];
	}

	private $repoFinder;
	private $tokenStorage;
	private $singleLoginProvider;
	private $twig;

	public function __construct(RepositoryFinder $repoFinder, TokenStorageInterface $tokenStorage, $singleLoginProvider, \Twig\Environment $twig) {
		$this->repoFinder = $repoFinder;
		$this->tokenStorage = $tokenStorage;
		$this->singleLoginProvider = $singleLoginProvider;
		$this->twig = $twig;
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

	public function onKernelException(GetResponseForExceptionEvent $event) {
		$exception = $event->getException();
		$statusCode = $exception instanceof HttpException ? $exception->getStatusCode() : ($exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
		$response = new Response($this->twig->render('exception.html.twig', ['exception' => $exception]), $statusCode);
		$event->setResponse($response);
	}

	private function initTokenStorage() {
		$user = $this->initUser();
		$token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, null, 'User', $user->getRoles());
		$this->tokenStorage->setToken($token);
	}

	private function initUser() {
		if (!$this->singleLoginProvider) {
			return User::createAnonymousUser();
		}
		$chitankaUser = (require $this->singleLoginProvider)();
		if ($chitankaUser['username']) {
			$repo = $this->repoFinder->forUser();
			return $repo->findByUsername($chitankaUser['username']) ?: $repo->createUser($chitankaUser['username'], $chitankaUser['email']);
		}
		return User::createAnonymousUser();
	}

}
