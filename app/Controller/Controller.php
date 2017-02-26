<?php namespace App\Controller;

use App\Entity\Book;
use App\Library\ShelfStore;
use App\Persistence\Manager;
use App\Persistence\RepositoryFinder;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

	const ITEMS_PER_PAGE = 24;

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

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager(new DoctrineORMAdapter($query), $request, $maxPerPage);
	}

	protected function collectionPager(Request $request, Collection $collection, $maxPerPage = null) {
		return $this->createPager(new DoctrineCollectionAdapter($collection), $request, $maxPerPage);
	}

	/**
	 * @param Book[] $books
	 * @return FormView[]
	 */
	protected function createAddToShelfForms($books) {
		if (!$this->getUser()) {
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

	private function createPager($adapter, Request $request, $maxPerPage) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->query->get('page', 1));
		return $pager;
	}
}
