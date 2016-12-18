<?php namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/docs")
 */
class DocsController extends Controller {

	/**
	 * @Route("/", name="docs")
	 */
	public function indexAction() {
		return $this->render('Docs/index.html.twig');
	}

	/**
	 * @Route("/books", name="docs_books")
	 */
	public function booksAction() {
		return $this->render('Docs/books.html.twig');
	}
}
