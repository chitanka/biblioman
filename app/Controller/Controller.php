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

	/** @return BookRepository */
	protected function bookRepo() {
		return $this->getDoctrine()->getManager()->getRepository('App:Book');
	}

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager(new DoctrineORMAdapter($query), $request, $maxPerPage);
	}

	protected function collectionPager(Request $request, Collection $collection, $maxPerPage = null) {
		return $this->createPager(new DoctrineCollectionAdapter($collection), $request, $maxPerPage);
	}

	private function createPager($adapter, Request $request, $maxPerPage) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->query->get('page', 1));
		return $pager;
	}
}
