<?php namespace App\Controller;

use App\Entity\Query\BookQuery;
use App\Entity\Shelf;
use App\Http\Request;
use App\Library\Librarian;
use App\Library\ShelfStore;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shelves")
 */
class ShelfController extends Controller {

	/**
	 * @Route("/", name="shelves")
	 */
	public function indexAction(Request $request, ShelfStore $shelfStore) {
		$pager = $this->pager($request, $shelfStore->showPublicShelves($request->getShelfGroup()));
		return $this->render('Shelf/index.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/{id}.{_format}", name="shelf", defaults={"_format": "html"})
	 */
	public function shelfAction(Shelf $shelf, Request $request, Librarian $librarian, ShelfStore $shelfStore, $_format) {
		$this->assertUserCanViewShelf($shelf, $shelfStore);
		return $this->renderShelf($shelf, $request, $librarian, $shelfStore, 'Shelf/shelf.html.twig', $_format);
	}

	protected function renderShelf(Shelf $shelf, Request $request, Librarian $librarian, ShelfStore $shelfStore, $template, $format) {
		$criteria = $librarian->createBookSearchCriteria($request->getSearchQuery(), $request->getBookSort());
		$result = $librarian->findBooksOnShelfByCriteria($shelf, $criteria);
		$pager = $this->pager($request, $result);
		if ($format === self::FORMAT_CSV) {
			return $this->renderBookExport($pager);
		}
		return $this->render($template, [
			'shelf' => $shelf,
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($shelfStore, $librarian->getBooksFromSearchResult($pager->getCurrentPageResults())),
			'searchAction' => $this->generateUrl($request->getCurrentRoute(), ['id' => $shelf->getId()]),
			'searchScope' => 'shelf',
		]);
	}

	protected function assertUserCanViewShelf(Shelf $shelf, ShelfStore $shelfStore) {
		$this->denyAccessUnless($shelfStore->userCanViewShelf($this->getAppUser(), $shelf));
	}

}
