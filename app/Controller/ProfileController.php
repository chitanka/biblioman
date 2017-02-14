<?php namespace App\Controller;

use App\Entity\BookRepository;
use App\Entity\Shelf;
use App\Entity\ShelfRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/my")
 */
class ProfileController extends Controller {

	/**
	 * @Route("/shelves", name="my_shelves")
	 */
	public function shelvesAction(Request $request) {
		$pager = $this->pager($request, $this->shelfRepo()->forUser($this->getUser()));
		return $this->render('Profile/shelves.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/shelves/{id}", name="my_shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		if ($shelf->getCreator() != $this->getUser()) {
			throw $this->createAccessDeniedException();
		}
		$pager = $this->collectionPager($request, $shelf->getBooksOnShelf());
		return $this->render('Profile/shelf.html.twig', [
			'shelf' => $shelf,
			'pager' => $pager,
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/** @return ShelfRepository */
	protected function shelfRepo() {
		return $this->getDoctrine()->getManager()->getRepository('App:Shelf');
	}

}
