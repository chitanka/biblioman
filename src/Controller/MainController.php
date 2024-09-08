<?php namespace App\Controller;

use App\Entity\Query\BookQuery;
use App\Http\Request;
use App\Library\Librarian;
use App\Library\ShelfStore;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller {

	protected int $responseAge = 3600; // 1 hour

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request, Librarian $librarian, ShelfStore $shelfStore) {
		$pager = $this->pager($request, $librarian->findRecentBooks());
		return $this->render('Main/index.html.twig', [
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($shelfStore, $pager->getCurrentPageResults()),
		]);
	}

}
