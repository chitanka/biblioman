<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRepository;
use App\Entity\Shelf;
use App\Entity\ShelfRepository;
use App\Entity\User;
use App\Form\ShelfType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

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
			$this->addSuccessFlash('shelf.saved', ['%shelf%' => $newShelf->getName()]);
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
		if (!$this->userCanViewShelf($this->getUser(), $shelf)) {
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
		if (!$this->userCanEditShelf($this->getUser(), $shelf)) {
			throw $this->createAccessDeniedException();
		}
		if (!$this->shelfRepo()->hasBookOnShelf($book, $shelf)) {
			$shelf->addBook($book);
			$this->save($shelf);
		}
		return $this->redirectToMyShelf($shelf);
	}

	/**
	 * @Route("/shelves/{id}/books/{book_id}", name="my_shelf_remove_book")
	 * @ParamConverter("book", options={"id": "book_id"})
	 * @Method({"DELETE"})
	 */
	public function removeFromShelfAction(Shelf $shelf, Book $book) {
		if (!$this->userCanEditShelf($this->getUser(), $shelf)) {
			throw $this->createAccessDeniedException();
		}
		if ($bookOnShelf = $this->shelfRepo()->findBookOnShelf($book, $shelf)) {
			$shelf->removeBook($bookOnShelf);
			$this->save($shelf);
		}
		return $this->redirectToMyShelf($shelf);
	}

	protected function redirectToMyShelf(Shelf $shelf) {
		return $this->redirectToRoute('my_shelf', ['id' => $shelf->getId()]);
	}

	/** @return ShelfRepository */
	protected function shelfRepo() {
		return $this->repo(Shelf::class);
	}

	protected function userCanViewShelf(User $user, Shelf $shelf) {
		return $shelf->getCreator() == $user;
	}

	protected function userCanEditShelf(User $user, Shelf $shelf) {
		return $shelf->getCreator() == $user;
	}
}
