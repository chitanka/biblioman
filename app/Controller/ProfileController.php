<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookOnShelf;
use App\Entity\Shelf;
use App\Form\ShelfType;
use App\Entity\Repository\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/my")
 */
class ProfileController extends Controller {

	/**
	 * @Route("/shelves", name="my_shelves")
	 */
	public function shelvesAction(Request $request) {
		$shelfStore = $this->shelfStore();
		$newShelf = $shelfStore->createShelf($this->getUser());
		$createForm = $this->createForm(ShelfType::class, $newShelf);
		if ($createForm->handleRequest($request)->isSubmitted() && $createForm->isValid()) {
			$shelfStore->saveShelf($newShelf);
			$this->addSuccessFlash('shelf.created', ['%shelf%' => $newShelf->getName()]);
		}
		$pager = $this->pager($request, $this->repoFinder()->forShelf()->forUser($this->getUser(), $request->query->get('group')));
		return $this->render('Profile/shelves.html.twig', [
			'pager' => $pager,
			'createForm' => $createForm->createView(),
		]);
	}

	/**
	 * @Route("/shelves/{id}", name="my_shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		$this->assertUserCanViewShelf($shelf);
		$pager = $this->collectionPager($request, $shelf->getBooksOnShelf());
		$books = array_map(function(BookOnShelf $bs) {
			return $bs->getBook();
		}, $pager->getCurrentPageResults());
		return $this->render('Profile/shelf.html.twig', [
			'shelf' => $shelf,
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($books),
		]);
	}

	/**
	 * @Route("/shelves/{id}/form", name="my_shelf_form")
	 */
	public function shelfFormAction(Shelf $shelf, Request $request) {
		$this->assertUserCanEditShelf($shelf);
		if ($request->isMethod('DELETE')) {
			$this->shelfStore()->deleteShelf($shelf);
			$this->addSuccessFlash('shelf.deleted', ['%shelf%' => $shelf->getName()]);
			return $this->redirectToRoute('my_shelves');
		}
		$form = $this->createForm(ShelfType::class, $shelf);
		if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
			$this->shelfStore()->saveShelf($shelf);
			$this->addSuccessFlash('shelf.saved', ['%shelf%' => $shelf->getName()]);
			return $this->redirectToMyShelf($shelf);
		}
		return $this->render('Profile/shelfForm.html.twig', [
			'form' => $form->createView(),
			'shelf' => $shelf,
		]);
	}

	/**
	 * @Route("/shelves/{id}/books", name="my_shelf_books")
	 */
	public function shelfBooksActions(Shelf $shelf) {
		return $this->redirectToMyShelf($shelf);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_add_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"POST"})
	 */
	public function addToShelfAction(Shelf $shelf, Book $book) {
		$this->assertUserCanEditShelf($shelf);
		$this->shelfStore()->putBookOnShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf, Response::HTTP_CREATED);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_remove_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"DELETE"})
	 */
	public function removeFromShelfAction(Shelf $shelf, Book $book) {
		$this->assertUserCanEditShelf($shelf);
		$this->shelfStore()->removeBookFromShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf);
	}

	protected function redirectToMyShelf(Shelf $shelf, $statusCode = Response::HTTP_SEE_OTHER) {
		return $this->redirectToRoute('my_shelf', ['id' => $shelf->getId()], $statusCode);
	}

	protected function assertUserCanHaveShelves() {
		$this->denyAccessUnless($this->shelfStore()->userCanHaveShelves($this->getUser()));
	}

	protected function assertUserCanViewShelf(Shelf $shelf) {
		$this->denyAccessUnless($this->shelfStore()->userCanViewShelf($this->getUser(), $shelf));
	}

	protected function assertUserCanEditShelf(Shelf $shelf) {
		$this->denyAccessUnless($this->shelfStore()->userCanEditShelf($this->getUser(), $shelf));
	}

}
