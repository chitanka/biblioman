<?php namespace App\Controller;

use App\Entity\BookOnShelf;
use App\Entity\BookRepository;
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
		$pager = $this->pager($request, $this->shelfRepo()->isPublic());
		return $this->render('Shelf/index.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/{id}", name="shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		if (!$this->userCanViewShelf($shelf)) {
			throw $this->createAccessDeniedException();
		}
		$pager = $this->collectionPager($request, $shelf->getBooksOnShelf());
		$books = array_map(function(BookOnShelf $bs) {
			return $bs->getBook();
		}, $pager->getCurrentPageResults());
		return $this->render('Shelf/shelf.html.twig', [
			'shelf' => $shelf,
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($books),
		]);
	}

	protected function userCanViewShelf(Shelf $shelf) {
		return $shelf->isPublic() || $shelf->getCreator() == $this->getUser();
	}

}
