<?php namespace App\Controller;

use App\Entity\Query\BookQuery;
use App\Entity\Shelf;
use App\Http\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/shelves")
 */
class ShelfController extends Controller {

	/**
	 * @Route("/", name="shelves")
	 */
	public function indexAction(Request $request) {
		$pager = $this->pager($request, $this->shelfStore()->showPublicShelves($request->getShelfGroup()));
		return $this->render('Shelf/index.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/{id}.{_format}", name="shelf", defaults={"_format": "html"})
	 */
	public function shelfAction(Shelf $shelf, Request $request, $_format) {
		$this->assertUserCanViewShelf($shelf);
		return $this->renderShelf($shelf, $request, 'Shelf/shelf.html.twig', $_format);
	}

	protected function renderShelf(Shelf $shelf, Request $request, $template, $format) {
		$criteria = $this->librarian()->createBookSearchCriteria($request->getSearchQuery(), $request->getBookSort());
		$result = $this->librarian()->findBooksOnShelfByCriteria($shelf, $criteria);
		$pager = $this->pager($request, $result);
		if ($format === self::FORMAT_CSV) {
			return $this->renderBookExport($pager);
		}
		return $this->render($template, [
			'shelf' => $shelf,
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($this->librarian()->getBooksFromSearchResult($pager->getCurrentPageResults())),
			'searchAction' => $this->generateUrl($request->getCurrentRoute(), ['id' => $shelf->getId()]),
			'searchScope' => 'shelf',
		]);
	}

	protected function assertUserCanViewShelf(Shelf $shelf) {
		$this->denyAccessUnless($this->shelfStore()->userCanViewShelf($this->getAppUser(), $shelf));
	}

}
