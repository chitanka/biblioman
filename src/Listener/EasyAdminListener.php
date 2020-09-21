<?php namespace App\Listener;

use App\Entity\Book;
use Doctrine\ORM\EntityManager;
#use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminListener implements EventSubscriberInterface {

	public static function getSubscribedEvents() {
		return [
//			EasyAdminEvents::POST_PERSIST => ['notifyRocketChatAboutCreation'],
//			EasyAdminEvents::POST_UPDATE => ['saveNewBookCovers'],
		];
	}

	public function notifyRocketChatAboutCreation(GenericEvent $event) {
		$book = $this->getBookFromEvent($event);
		if (!$book) {
			return;
		}
		$msg = "Нов запис в Библиоман: _[{$book->getTitleWithVolume()}](https://biblioman.chitanka.info/books/{$book->getId()})_ – добавен от **{$book->getCreatedBy()}**";
		$file = (new \DateTime('+20 minutes'))->format('Ymd_His').'-'.uniqid().'.msg';
		file_put_contents(__DIR__.'/../../var/rocketchat/'.$file, $msg);
	}

	public function saveNewBookCovers(GenericEvent $event) {
		$book = $this->getBookFromEvent($event);
		if (!$book) {
			return;
		}
		$em = $this->getEntityManagerFromEvent($event);
		foreach ($book->getCovers() as $cover) {
			if ($cover->isNew()) {
				$em->persist($cover);
			}
		}
		$em->flush();
	}

	/**
	 * @param GenericEvent $event
	 * @return Book|null
	 */
	private function getBookFromEvent(GenericEvent $event) {
		$entity = $event->getSubject();
		if ($entity instanceof Book) {
			return $entity;
		}
		return null;
	}

	/**
	 * @param GenericEvent $event
	 * @return EntityManager
	 */
	private function getEntityManagerFromEvent(GenericEvent $event) {
		return $event->getArgument('em');
	}
}
