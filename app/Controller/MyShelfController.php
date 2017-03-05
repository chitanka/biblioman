<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Form\ShelfType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/my")
 */
class MyShelfController extends ShelfController {

	/**
	 * @Route("/shelves", name="my_shelves")
	 */
	public function shelvesAction(Request $request) {
		$this->assertUserCanHaveShelves();
		$shelfStore = $this->shelfStore();
		$newShelf = $shelfStore->createShelf($this->getUser());
		$createForm = $this->createForm(ShelfType::class, $newShelf);
		if ($createForm->handleRequest($request)->isSubmitted() && $createForm->isValid()) {
			$shelfStore->saveShelf($newShelf);
			$this->addSuccessFlash('shelf.created', ['%shelf%' => $newShelf->getName()]);
		}
		$pager = $this->pager($request, $shelfStore->showUserShelves($this->getUser(), $request->query->get('group')));
		return $this->render('Profile/shelves.html.twig', [
			'pager' => $pager,
			'createForm' => $createForm->createView(),
			'searchAction' => $this->generateUrl($request->get('_route')),
			'searchScope' => 'shelves',
		]);
	}

	/**
	 * @Route("/shelves/{id}", name="my_shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		$this->assertUserOwnsShelf($shelf);
		return $this->renderShelf($shelf, $request, 'Profile/shelf.html.twig');
	}

	/**
	 * @Route("/shelves/{id}/form", name="my_shelf_form")
	 */
	public function shelfFormAction(Shelf $shelf, Request $request) {
		$this->assertUserOwnsShelf($shelf);
		if ($request->isMethod(Request::METHOD_DELETE)) {
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
		$this->assertUserOwnsShelf($shelf);
		$this->shelfStore()->putBookOnShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf, Response::HTTP_CREATED);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_remove_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"DELETE"})
	 */
	public function removeFromShelfAction(Shelf $shelf, Book $book) {
		$this->assertUserOwnsShelf($shelf);
		$this->shelfStore()->removeBookFromShelf($book, $shelf);
		return $this->redirectToMyShelf($shelf);
	}

	protected function redirectToMyShelf(Shelf $shelf, $statusCode = Response::HTTP_SEE_OTHER) {
		return $this->redirectToRoute('my_shelf', ['id' => $shelf->getId()], $statusCode);
	}

	protected function assertUserCanHaveShelves() {
		$this->denyAccessUnless($this->shelfStore()->userCanHaveShelves($this->getUser()));
	}

	protected function assertUserOwnsShelf(Shelf $shelf) {
		$this->denyAccessUnless($this->shelfStore()->userOwnsShelf($this->getUser(), $shelf));
	}

}
