<?php namespace App\Controller;

use App\Collection\Books;
use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Query\BookQuery;
use App\File\Thumbnail;
use App\Http\Request;
use App\Library\BookSearchCriteria;
use App\Library\Librarian;
use App\Library\ShelfStore;
use App\Persistence\RepositoryFinder;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books")
 */
class BookController extends Controller {

	/**
	 * @Route(".{_format}", name="books", defaults={"_format": "html"})
	 */
	public function indexAction(Request $request, Librarian $librarian, ShelfStore $shelfStore, $_format) {
		$searchQuery = $librarian->createBookSearchCriteria($request->getSearchQuery(), $request->getBookSort());
		$pager = $this->pager($request, $librarian->findBooksByCriteria($searchQuery));
		$fields = $this->getParameter('book_fields_short');
		if ($searchQuery->field && !in_array($searchQuery->field, $fields)) {
			// include the search field in the book output
			$fields[] = $searchQuery->field;
		}
		return $this->renderBookListing('Book/index.html.twig', $pager, $shelfStore, [
			'fields' => $fields,
			'query' => $searchQuery,
		], $_format, $request);
	}

	/**
	 * @Route("/categories", name="books_categories")
	 */
	public function listCategoriesAction(RepositoryFinder $repoFinder) {
		return $this->render('Book/listCategories.html.twig', [
			'tree' => $this->generateCategoryTree($repoFinder),
		]);
	}

	/**
	 * @Route("/categories/{slug}.{_format}", name="books_by_category", defaults={"_format": "html"})
	 */
	public function listByCategoryAction(Request $request, ShelfStore $shelfStore, RepositoryFinder $repoFinder, BookCategory $category, $_format) {
		$pager = $this->pager($request, $repoFinder->forBook()->filterByCategory($category));
		return $this->renderBookListing('Book/listByCategory.html.twig', $pager, $shelfStore, [
			'category' => $category,
			'categoryPath' => $repoFinder->forBookCategory()->getPath($category),
			'tree' => $this->generateCategoryTree($repoFinder, $category),
		], $_format, $request);
	}

	/**
	 * @Route("/incomplete.{_format}", name="books_incomplete", defaults={"_format": "html"})
	 */
	public function listIncompleteAction(Request $request, Librarian $librarian, ShelfStore $shelfStore, RepositoryFinder $repoFinder, $_format) {
		$searchQuery = $librarian->createBookSearchCriteria($request->getSearchQuery(), $request->getBookSort());
		$pager = $this->pager($request, $repoFinder->forBook()->filterIncomplete($searchQuery));
		return $this->renderBookListing('Book/listIncomplete.html.twig', $pager, $shelfStore, [], $_format, $request);
	}

	/**
	 * @Route("/search-duplicates", name="books_search_duplicates")
	 */
	public function searchDuplicatesAction(Request $request, RepositoryFinder $repoFinder) {
		$books = $repoFinder->forBook()->findDuplicatesByTitle($request->query->get('title'), $request->query->get('id'));
		return $this->render('Book/searchDuplicates.html.twig', [
			'books' => $books,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/revisions", name="books_revisions")
	 */
	public function showAllRevisionsAction(Request $request, RepositoryFinder $repoFinder) {
		$pager = $this->pager($request, $repoFinder->forBook()->revisions(), 30);
		return $this->render('Book/showAllRevisions.html.twig', [
			'pager' => $pager,
		]);
	}

	/**
	 * @Route("/{id}/revisions", name="books_show_revisions")
	 */
	public function showRevisionsAction(Book $book) {
		return $this->render('Book/showRevisions.html.twig', [
			'book' => $book,
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/{id}/verify", name="books_verify", methods={"PUT"})
	 */
	public function verifyAction(Book $book) {
		$book->verify($this->getAppUser());
		$em = $this->getDoctrine()->getManager();
		$em->persist($book);
		$em->flush();
		$this->addFlash('success', 'Верифициран запис');
		return $this->redirectToRoute('books_show', ['id' => $book->getId()]);
	}

	/**
	 * @Route("/{id}.{_format}", defaults={"_format" = "html"}, name="books_show")
	 */
	public function showAction(Request $request, ShelfStore $shelfStore, Book $book, $_format) {
		switch ($_format) {
			case 'cover':
				return $this->redirect(Thumbnail::createCoverPath($book->getCover(), $request->get('size', 300)));
			case self::FORMAT_JSON:
				return $this->json($book);
		}
		return $this->render('Book/show.html.twig', [
			'book' => $book,
			'fields' => $this->getParameter('book_fields_long'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms($shelfStore, [$book]),
		]);
	}

	/**
	 * @Route("/{id}/covers", defaults={"_format" = "html"}, name="books_show_covers")
	 */
	public function showCoversAction(Book $book) {
		return $this->render('Book/showCovers.html.twig', [
			'book' => $book,
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/{id}/scans", defaults={"_format" = "html"}, name="books_show_scans")
	 */
	public function showScansAction(Book $book) {
		return $this->render('Book/showScans.html.twig', [
			'book' => $book,
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
		]);
	}

	private function renderBookListing($template, Pagerfanta $pager, ShelfStore $shelfStore, $viewVariables, $format, Request $request) {
		switch ($format) {
			case self::FORMAT_CSV:
				return $this->renderBookExport($pager);
			case self::FORMAT_JSON:
				return $this->renderResultsAsJson($pager->getCurrentPageResults(), $pager, $request);
		}
		return $this->render($template, array_merge([
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'sortableFields' => BookQuery::$sortableFields,
			'addToShelfForms' => $this->createAddToShelfForms($shelfStore, $pager->getCurrentPageResults()),
		], $viewVariables));
	}

	protected function renderResultsAsJson($data, Pagerfanta $pager, Request $request) {
		$host = $request->getSchemeAndHttpHost();
		$books = new Books($data);
		$books->setJsonFormatter(function (Book $book) use ($host) {
			$bookData = $book->jsonSerialize();
			$bookData['urls'] = array_map(function($path) use ($host) {
				return $host.$path;
			}, $bookData['urls']);
			return array_merge_recursive($bookData, ['urls' => ['canonical' => $this->generateAbsoluteUrl('books_show', ['id' => $book->getId()])]]);
		});
		return parent::renderResultsAsJson($books, $pager, $request);
	}

	/**
	 * @param BookCategory $rootCategory
	 * @return string
	 */
	private function generateCategoryTree(RepositoryFinder $repoFinder, $rootCategory = null) {
		return $repoFinder->forBookCategory()->childrenHierarchy($rootCategory, false /* false: load only direct children */, $this->categoryTreeOptions());
	}

	private function categoryTreeOptions() {
		return [
			'decorate' => true,
			'rootOpen' => '<ul>',
			'rootClose' => '</ul>',
			'childOpen' => '<li>',
			'childClose' => '</li>',
			'nodeDecorator' => function($category) {
				return '<a href="'.$this->generateUrl('books_by_category', ['slug' => $category['slug']]).'">'.$category['name'].'</a>';
			}
		];
	}
}
