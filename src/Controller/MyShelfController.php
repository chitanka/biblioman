<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Form\ShelfType;
use App\Http\Request;
use App\Library\Librarian;
use App\Library\ShelfStore;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/my")
 */
class MyShelfController extends ShelfController {

	/**
	 * @Route("/shelves", name="my_shelves")
	 */
	public function shelvesAction(Request $request, ShelfStore $shelfStore) {
		$this->assertUserCanHaveShelves($shelfStore);
		$newShelf = $shelfStore->createShelf($this->getAppUser());
		$createForm = $this->createForm(ShelfType::class, $newShelf);
		if ($createForm->handleRequest($request)->isSubmitted() && $createForm->isValid()) {
			$shelfStore->saveShelf($newShelf);
			$this->addSuccessFlash('shelf.created', ['%shelf%' => $newShelf->getName()]);
		}
		$pager = $this->pager($request, $shelfStore->showUserShelves($this->getAppUser(), $request->getShelfGroup()));
		return $this->render('Profile/shelves.html.twig', [
			'pager' => $pager,
			'createForm' => $createForm->createView(),
			'searchAction' => $this->generateUrl($request->getCurrentRoute()),
			'searchScope' => 'shelves',
		]);
	}

	/**
	 * @Route("/shelves/{id}.{_format}", name="my_shelf", defaults={"_format": "html"})
	 */
	public function shelfAction(Shelf $shelf, Request $request, Librarian $librarian, ShelfStore $shelfStore, $_format) {
		$this->assertUserOwnsShelf($shelf, $shelfStore);
		return $this->renderShelf($shelf, $request, $librarian, $shelfStore, 'Profile/shelf.html.twig', $_format);
	}

	/**
	 * @Route("/shelves/{id}/form", name="my_shelf_form")
	 */
	public function shelfFormAction(Shelf $shelf, Request $request, ShelfStore $shelfStore) {
		$this->assertUserOwnsShelf($shelf, $shelfStore);
		if ($request->isMethod(Request::METHOD_DELETE)) {
			$shelfStore->deleteShelf($shelf);
			$this->addSuccessFlash('shelf.deleted', ['%shelf%' => $shelf->getName()]);
			return $this->redirectToRoute('my_shelves');
		}
		$form = $this->createForm(ShelfType::class, $shelf);
		if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
			$shelfStore->saveShelf($shelf);
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
	public function addToShelfAction(Shelf $shelf, Book $book, ShelfStore $shelfStore) {
		$this->assertUserOwnsShelf($shelf, $shelfStore);
		$shelfStore->putBookOnShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf, Response::HTTP_CREATED);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_remove_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"DELETE"})
	 */
	public function removeFromShelfAction(Shelf $shelf, Book $book, ShelfStore $shelfStore) {
		$this->assertUserOwnsShelf($shelf, $shelfStore);
		$shelfStore->removeBookFromShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf);
	}

	protected function redirectToMyShelf(Shelf $shelf, $statusCode = Response::HTTP_SEE_OTHER) {
		return $this->redirectToRoute('my_shelf', ['id' => $shelf->getId()], $statusCode);
	}

	protected function assertUserCanHaveShelves(ShelfStore $shelfStore) {
		$this->denyAccessUnless($shelfStore->userCanHaveShelves($this->getAppUser()));
	}

	protected function assertUserOwnsShelf(Shelf $shelf, ShelfStore $shelfStore) {
		$this->denyAccessUnless($shelfStore->userOwnsShelf($this->getAppUser(), $shelf));
	}

}
