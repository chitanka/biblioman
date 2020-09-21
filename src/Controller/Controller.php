<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Http\CsvResponse;
use App\Http\Request;
use App\Library\BookExport;
use App\Library\ShelfStore;
use App\Persistence\Manager;
use App\Persistence\RepositoryFinder;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\Doctrine\Collections\CollectionAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormView;

abstract class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

	const ITEMS_PER_PAGE = 24;

	const FORMAT_CSV = 'csv';
	const FORMAT_JSON = 'json';

	protected function getAppUser(): User {
		return parent::getUser() ?? User::createAnonymousUser();
	}

	/** @return Manager */
	protected function persistenceManager() {
		return $this->get('app.persistence_manager');
	}

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager($this->createPagerAdapter($query), $request, $maxPerPage);
	}

	private function createPagerAdapter($query) {
		if ($query instanceof Collection) {
			return new CollectionAdapter($query);
		}
		return new QueryAdapter($query);
	}

	/**
	 * @param array|\Traversable|Book[] $books
	 * @return FormView[]
	 */
	protected function createAddToShelfForms(ShelfStore $shelfStore, $books) {
		if ($this->getAppUser()->isAnonymous()) {
			return null;
		}
		return $shelfStore->createAddToShelfForms($books, $this->createFormBuilder(), $this->getAppUser(), $this->getParameter('default_shelves'));
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

	protected function renderResultsAsJson($data, Pagerfanta $pager, Request $request) {
		$output = [
			'results' => $data,
			'page' => $pager->getCurrentPage(),
			'nbPages' => $pager->getNbPages(),
			'nbResults' => $pager->getNbResults(),
		];
		if ($pager->hasPreviousPage()) {
			$output['prev'] = $this->generateAbsoluteUrl($request->getCurrentRoute(), [Request::PARAM_PAGER_PAGE => $pager->getPreviousPage()] + $request->getAllParams());
		}
		if ($pager->hasNextPage()) {
			$output['next'] = $this->generateAbsoluteUrl($request->getCurrentRoute(), [Request::PARAM_PAGER_PAGE => $pager->getNextPage()] + $request->getAllParams());
		}
		return $this->json($output, 200, ['Access-Control-Allow-Origin' => '*']);
	}

	protected function generateAbsoluteUrl($route, $params) {
		return $this->generateUrl($route, $params, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
	}

	private function createPager($adapter, Request $request, $maxPerPage) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->getPagerPage());
		return $pager;
	}
}
