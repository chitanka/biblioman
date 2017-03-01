<?php namespace App\Controller;

use App\Entity\Query\BookQuery;
use App\Entity\Shelf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shelves")
 */
class ShelfController extends Controller {

	/**
	 * @Route("/", name="shelves")
	 */
	public function indexAction(Request $request) {
		$pager = $this->pager($request, $this->repoFinder()->forShelf()->isPublic($request->query->get('group')));
		return $this->render('Shelf/index.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/{id}", name="shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		$this->assertUserCanViewShelf($shelf);
		return $this->renderShelf($shelf, $request, 'Shelf/shelf.html.twig');
	}

	protected function renderShelf(Shelf $shelf, Request $request, $template) {
		$searchQuery = $this->librarian()->createBookSearchQuery($request->query->get('q'), $request->query->get('sort'));
		$result = $this->librarian()->findBooksOnShelfByQuery($shelf, $searchQuery);
		$pager = $this->pager($request, $result);
		return $this->render($template, [
			'shelf' => $shelf,
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($this->librarian()->getBooksFromSearchResult($pager->getCurrentPageResults())),
			'searchAction' => $this->generateUrl($request->get('_route'), ['id' => $shelf->getId()]),
			'searchScope' => 'shelf',
		]);
	}

	protected function assertUserCanViewShelf(Shelf $shelf) {
		$this->denyAccessUnless($this->shelfStore()->userCanViewShelf($this->getUser(), $shelf));
	}

}
