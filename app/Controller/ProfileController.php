<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRepository;
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
class ProfileController extends Controller {

	/**
	 * @Route("/shelves", name="my_shelves")
	 */
	public function shelvesAction(Request $request) {
		$newShelf = new Shelf($this->getUser());
		$createForm = $this->createForm(ShelfType::class, $newShelf);
		if ($createForm->handleRequest($request)->isValid()) {
			$this->save($newShelf);
			$this->addSuccessFlash('shelf.created', ['%shelf%' => $newShelf->getName()]);
		}
		$pager = $this->pager($request, $this->shelfRepo()->forUser($this->getUser()));
		return $this->render('Profile/shelves.html.twig', [
			'pager' => $pager,
			'createForm' => $createForm->createView(),
		]);
	}

	/**
	 * @Route("/shelves/{id}", name="my_shelf")
	 */
	public function shelfAction(Shelf $shelf, Request $request) {
		if (!$this->userCanViewShelf($shelf)) {
			throw $this->createAccessDeniedException();
		}
		$pager = $this->collectionPager($request, $shelf->getBooksOnShelf());
		return $this->render('Profile/shelf.html.twig', [
			'shelf' => $shelf,
			'pager' => $pager,
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/shelves/{id}/form", name="my_shelf_form")
	 */
	public function shelfFormAction(Shelf $shelf, Request $request) {
		if (!$this->userCanEditShelf($shelf)) {
			throw $this->createAccessDeniedException();
		}
		if ($request->isMethod('DELETE')) {
			$this->delete($shelf);
			$this->addSuccessFlash('shelf.deleted', ['%shelf%' => $shelf->getName()]);
			return $this->redirectToRoute('my_shelves');
		}
		$form = $this->createForm(ShelfType::class, $shelf);
		if ($form->handleRequest($request)->isValid()) {
			$this->save($shelf);
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
		if (!$this->userCanEditShelf($shelf)) {
			throw $this->createAccessDeniedException();
		}
		if (!$this->shelfRepo()->hasBookOnShelf($book, $shelf)) {
			$shelf->addBook($book);
			$this->save($shelf);
		}
		return $this->redirectToMyShelf($shelf, Response::HTTP_CREATED);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_remove_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"DELETE"})
	 */
	public function removeFromShelfAction(Shelf $shelf, Book $book, Request $request) {
		if (!$this->userCanEditShelf($shelf)) {
			throw $this->createAccessDeniedException();
		}
		if ($bookOnShelf = $this->shelfRepo()->findBookOnShelf($book, $shelf)) {
			$shelf->removeBook($bookOnShelf);
			$this->save($shelf);
		}
		return $this->redirectToMyShelf($shelf);
	}

	protected function redirectToMyShelf(Shelf $shelf, $statusCode = Response::HTTP_SEE_OTHER) {
		return $this->redirectToRoute('my_shelf', ['id' => $shelf->getId()], $statusCode);
	}

	protected function userCanViewShelf(Shelf $shelf) {
		return $shelf->getCreator() == $this->getUser();
	}

	protected function userCanEditShelf(Shelf $shelf) {
		return $shelf->getCreator() == $this->getUser();
	}
}
