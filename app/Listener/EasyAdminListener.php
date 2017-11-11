<?php namespace App\Listener;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminListener implements EventSubscriberInterface {

	public static function getSubscribedEvents() {
		return [
			EasyAdminEvents::POST_UPDATE => ['saveNewBookCovers'],
		];
	}

	public function saveNewBookCovers(GenericEvent $event) {
		$entity = $event->getSubject();

		if (!($entity instanceof Book)) {
			return;
		}

		$em = $event->getArgument('em');
		foreach ($entity->getCovers() as $cover) {
			if ($cover->isNew()) {
				$em->persist($cover);
			}
		}
		$em->flush();
	}
}
