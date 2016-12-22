<?php namespace App\Controller;

use App\Entity\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
		$recentBooks = $this->getDoctrine()->getManager()
			->getRepository('App:Book')
			->findRecent(10);
		return $this->render('Main/index.html.twig', [
			'recentBooks' => $recentBooks,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}
}
