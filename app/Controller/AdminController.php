<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class AdminController extends EasyAdminController {

	/** @var Book */
	private $bookPreEdit;

	/**
	 * @Route("/admin/", name="easyadmin")
	 */
	public function indexAction(Request $request) {
		$response = parent::indexAction($request);
		if (isset($this->entity['role'])) {
			$this->denyAccessUnlessGranted($this->entity['role']);
		}
		return $response;
	}

	protected function prePersistBookEntity(Book $book) {
		return $book->setCreatedBy($this->getUsername());
	}

	protected function preUpdateBookEntity(Book $book) {
		$diffs = $this->bookPreEdit->getDifferences($book);
		if ($diffs) {
			$revision = $book->createRevision();
			$revision->setDiffs($diffs);
			$revision->setCreatedBy($this->getUsername());
			$this->em->persist($revision);
		}
		$book->clearLock();
	}

	protected function createBookEditForm(Book $book, $fields) {
		$form = $this->createEditForm($book, $fields);
		$this->bookPreEdit = clone $book;
		if ($book->isLockedForUser($this->getUsername())) {
			$form->addError(new FormError("В момента този запис се редактира от {$book->getLockedBy()}."));
		} else {
			$book->setLock($this->getUsername());
			$this->em->persist($book);
			$this->em->flush();
		}
		return $form;
	}

	protected function createBookEntityFormBuilder(Book $book, $view) {
		$builder = $this->createEntityFormBuilder($book, $view);
		if ($book->isLockedForUser($this->getUsername())) {
			$builder->setDisabled(true);
		}
		return $builder;
	}

	/** @return User */
	protected function getUser() {
		return $this->get('security.token_storage')->getToken()->getUser();
	}

	protected function getUsername() {
		return $this->getUser()->getUsername();
	}
}
