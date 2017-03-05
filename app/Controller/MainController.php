<?php namespace App\Controller;

use App\Entity\Query\BookQuery;
use App\Http\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request) {
		$pager = $this->pager($request, $this->librarian()->findRecentBooks());
		return $this->render('Main/index.html.twig', [
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($pager->getCurrentPageResults()),
		]);
	}
}
