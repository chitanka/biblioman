<?php namespace App\Listener;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminListener implements EventSubscriberInterface {

	/** @var EntityManagerInterface */
	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public static function getSubscribedEvents() {
		return [
			AfterEntityPersistedEvent::class => ['notifyRocketChatAboutCreation'],
			AfterEntityUpdatedEvent::class => ['saveNewBookCovers'],
		];
	}

	public function notifyRocketChatAboutCreation(AfterEntityPersistedEvent $event) {
		$book = $event->getEntityInstance();
		if (!$book instanceof Book) {
			return;
		}
		$msg = "Нов запис в Библиоман: _[{$book->getTitleWithVolume()}](https://biblioman.chitanka.info/books/{$book->getId()})_ – добавен от **{$book->createdBy}**";
		$file = (new \DateTime('+20 minutes'))->format('Ymd_His').'-'.uniqid().'.msg';
		file_put_contents(__DIR__.'/../../var/rocketchat/'.$file, $msg);
	}

	public function saveNewBookCovers(AfterEntityUpdatedEvent $event) {
		$book = $event->getEntityInstance();
		if (!$book instanceof Book) {
			return;
		}
		foreach ($book->getCovers() as $cover) {
			if ($cover->isNew()) {
				$this->em->persist($cover);
			}
		}
		$this->em->flush();
	}

}
