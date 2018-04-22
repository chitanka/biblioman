<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\Entity;
use Chitanka\WikiBundle\Service\WikiEngine;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends \EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController {

	/** @var Book */
	private $bookPreEdit;

	/**
	 * @Route("/admin/", name="easyadmin")
	 */
	public function indexAction(Request $request) {
		$response = parent::indexAction($request);
		return $response;
	}

	/**
	 * @Route("/admin/books/extend-lock")
	 */
	public function extendBookLock(Request $request) {
		$this->initialize($request);
		$book = $this->getEntity($request); /* @var $book Book */
		if ($book) {
			$book->extendLock();
			$this->em->flush($book);
			return new JsonResponse(true);
		}
		return new JsonResponse(false);
	}

	protected function initialize(Request $request) {
		parent::initialize($request);
		$this->checkUserRole();
		$this->checkUserAuthorization($request->query->get('action'), $request->attributes->get('easyadmin')['item']);
		$this->putHelpMessagesFromWiki();
	}

	protected function prePersistBookEntity(Book $book) {
		$book->setCreatorByNewFiles($this->getUsername());
		$book->setCreatedByUser($this->getUser());
	}

	protected function preUpdateBookEntity(Book $book) {
		$revision = $book->createRevisionIfNecessary($this->bookPreEdit, $this->getUsername());
		if ($revision) {
			$this->em->persist($revision);
		}
		$book->setCreatorByNewFiles($this->getUsername());
		$book->clearLock();
	}

	protected function createBookNewForm(Book $book, $fields) {
		$form = $this->createNewForm($book, $fields);
		if ($title = $this->request->query->get('title')) {
			$form->get('title')->setData($title);
		}
		return $form;
	}

	protected function createBookEditForm(Book $book, $fields) {
		$form = $this->createEditForm($book, $fields);
		$this->bookPreEdit = clone $book;
		if ($book->isLockedForUser($this->getUsername())) {
			$form->addError(new FormError("В момента този запис се редактира от {$book->getLockedBy()}."));
		} else {
			$book->disableUpdatedTracking();
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

	protected function getUsername() {
		return $this->getUser()->getUsername();
	}

	protected function checkUserRole() {
		if (isset($this->entity['role'])) {
			$this->denyAccessUnlessGranted($this->entity['role']);
		}
	}

	protected function checkUserAuthorization($action, $object) {
		if (!isset($this->entity[$action]['auth'])) {
			return;
		}
		$language = new ExpressionLanguage();
		foreach ($this->entity[$action]['auth'] as $key => $expression) {
			$params = [
				'user' => $this->getUser(),
				'object' => $object,
			];
			$isAllowed = $language->evaluate($expression, $params);
			if (!$isAllowed) {
				throw $this->createAccessDeniedException("auth.$key");
			}
		}
	}

	protected function putHelpMessagesFromWiki() {
		$easyadmin = $this->request->attributes->get('easyadmin');
		if ($easyadmin['entity']['name'] !== 'Book') {
			return;
		}
		$wiki = new WikiEngine($this->container->getParameter('chitanka_wiki.content_dir'));
		$action = $this->request->query->get('action');
		foreach ($easyadmin['entity'][$action]['fields'] as $fieldName => $field) {
			if ($fieldName[0] === '_') {
				continue;
			}
			$wikiPageName = str_replace('_', '-', Inflector::tableize($fieldName));
			$page = $wiki->getPage("docs/books/$wikiPageName", false);
			if ($page->exists()) {
				$easyadmin['entity'][$action]['fields'][$fieldName]['help'] = $page->getContentHtml().' <a href="/wiki/edit/docs/books/'.$wikiPageName.'" tabindex="-1" class="wiki-edit-link"><span class="fa fa-file-text-o"></span></a>';
			}
		}
		$this->request->attributes->set('easyadmin', $easyadmin);
	}

	protected function redirectToReferrer() {
		if ($this->actionIsNew()) {
			return $this->redirect($this->generateEditUrl());
		}
		return parent::redirectToReferrer();
	}

	protected function actionIsNew() {
		return $this->request->query->get('action') === 'new';
	}

	/**
	 * @param Request $request
	 * @return Entity
	 */
	protected function getEntity(Request $request = null) {
		if ($request === null) {
			$request = $this->request;
		}
		return $request->attributes->get('easyadmin')['item'];
	}

	protected function generateEditUrl(Entity $entity = null) {
		if ($entity === null) {
			$entity = $this->getEntity();
		}
		return $this->generateUrl('easyadmin', [
			'action' => 'edit',
			'entity' => $this->entity['name'],
			'id' => $entity->getId(),
		]);
	}
}
