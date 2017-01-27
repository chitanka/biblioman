<?php namespace App\Controller;

use App\Entity\BookRepository;
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
		$pager = new Pagerfanta(new DoctrineORMAdapter($query));
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->query->get('page', 1));
		return $pager;
	}
}
