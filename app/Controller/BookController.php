<?php namespace App\Controller;

use App\Entity\BookRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/books")
 */
class BookController extends Controller {

	const ITEMS_PER_PAGE = 15;

	/**
	 * @Route("/", name="books")
	 */
	public function indexAction(Request $request) {
		$searchQuery = $request->query->get('q');
		$adapter = new DoctrineORMAdapter($this->repo()->filterByQuery($searchQuery));
		$pager = $this->pager($request, $adapter);
		$searchQueryWoField = trim(array_slice(explode(BookRepository::FIELD_SEARCH_SEPARATOR, $searchQuery), -1)[0]);
		return $this->render('Book/index.html.twig', [
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
			'query' => $searchQuery,
			'queryWoField' => $searchQueryWoField,
		]);
	}

	/**
	 * @Route("/incomplete", name="books_incomplete")
	 */
	public function listIncompleteAction(Request $request) {
		$adapter = new DoctrineORMAdapter($this->repo()->filterIncomplete());
		$pager = $this->pager($request, $adapter);
		return $this->render('Book/listIncomplete.html.twig', [
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/search-duplicates", name="books_search_duplicates")
	 */
	public function searchDuplicatesAction(Request $request) {
		$books = $this->repo()->findDuplicatesByTitle($request->query->get('title'), $request->query->get('id'));
		return $this->render('Book/searchDuplicates.html.twig', [
			'books' => $books,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/{id}", name="books_show")
	 */
	public function showAction($id) {
		$book = $this->repo()->find($id);
		if (!$book) {
			throw $this->createNotFoundException('Book not found');
		}
		return $this->render('Book/show.html.twig', [
			'book' => $book,
			'fields' => $this->getParameter('book_fields_long'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	private function pager(Request $request, $adapter) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage(self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->query->get('page', 1));
		return $pager;
	}

	/** @return BookRepository */
	private function repo() {
		return $this->getDoctrine()->getManager()->getRepository('App:Book');
	}
}
