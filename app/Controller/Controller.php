<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Http\CsvResponse;
use App\Http\Request;
use App\Library\BookExport;
use App\Library\Librarian;
use App\Library\ShelfStore;
use App\Persistence\Manager;
use App\Persistence\RepositoryFinder;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormView;

abstract class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

	const ITEMS_PER_PAGE = 24;

	const FORMAT_CSV = 'csv';

	/** @return User */
	protected function getUser() {
		return parent::getUser() ?: User::createAnonymousUser();
	}

	/** @return Manager */
	protected function persistenceManager() {
		return $this->get('app.persistence_manager');
	}

	/** @return RepositoryFinder */
	protected function repoFinder() {
		return $this->get('app.repository_finder');
	}

	/** @return ShelfStore */
	protected function shelfStore() {
		return $this->get('app.shelf_store');
	}

	/** @return Librarian */
	protected function librarian() {
		return $this->get('app.librarian');
	}

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager($this->createPagerAdapter($query), $request, $maxPerPage);
	}

	private function createPagerAdapter($query) {
		if ($query instanceof Collection) {
			return new DoctrineCollectionAdapter($query);
		}
		return new DoctrineORMAdapter($query);
	}

	/**
	 * @param array|\Traversable|Book[] $books
	 * @return FormView[]
	 */
	protected function createAddToShelfForms($books) {
		if ($this->getUser()->isAnonymous()) {
			return null;
		}
		return $this->shelfStore()->createAddToShelfForms($books, $this->createFormBuilder(), $this->getUser(), $this->getParameter('default_shelves'));
	}

	protected function addSuccessFlash($message, $params = []) {
		$this->addFlash('success', $this->translate($message, $params));
	}

	protected function addErrorFlash($message, $params = []) {
		$this->addFlash('error', $this->translate($message, $params));
	}

	protected function translate($message, $params = []) {
		return $this->get('translator')->trans($message, $params);
	}

	protected function denyAccessUnless($assertion) {
		if (!$assertion) {
			throw $this->createAccessDeniedException();
		}
	}

	protected function renderBookExport(Pagerfanta $pager, $fieldsToExport = null) {
		if ($fieldsToExport === null) {
			$fieldsToExport = $this->getParameter('book_fields_export');
		}
		$data = BookExport::fromPager($pager)->toArray($fieldsToExport);
		return new CsvResponse($this->get('serializer')->encode($data, 'csv'));
	}

	private function createPager($adapter, Request $request, $maxPerPage) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->getPagerPage());
		return $pager;
	}
}
