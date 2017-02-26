<?php namespace App\Controller;

use App\Repository\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request) {
		$pager = $this->pager($request, $this->repoFinder()->forBook()->recent());
		return $this->render('Main/index.html.twig', [
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($pager->getCurrentPageResults()),
		]);
	}
}
