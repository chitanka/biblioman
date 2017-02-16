<?php namespace App\Controller;

use App\Entity\BookRepository;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends BaseController {

	const ITEMS_PER_PAGE = 24;

	protected function em() {
		return $this->getDoctrine()->getManager();
	}

	/** @return BookRepository */
	protected function bookRepo() {
		return $this->repo('App:Book');
	}

	protected function repo($repoClass) {
		return $this->em()->getRepository($repoClass);
	}

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager(new DoctrineORMAdapter($query), $request, $maxPerPage);
	}

	protected function collectionPager(Request $request, Collection $collection, $maxPerPage = null) {
		return $this->createPager(new DoctrineCollectionAdapter($collection), $request, $maxPerPage);
	}

	protected function save($entities) {
		if (!is_array($entities)) {
			$entities = [$entities];
		}
		$em = $this->em();
		foreach ($entities as $entity) {
			$em->persist($entity);
		}
		$em->flush();
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
