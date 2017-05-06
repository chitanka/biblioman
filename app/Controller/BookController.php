<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Query\BookQuery;
use App\File\Thumbnail;
use App\Http\Request;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/books")
 */
class BookController extends Controller {

	/**
	 * @Route("/", name="books")
	 */
	public function indexAction(Request $request) {
		$searchQuery = $this->librarian()->createBookSearchCriteria($request->getSearchQuery(), $request->getBookSort());
		$pager = $this->pager($request, $this->librarian()->findBooksByCriteria($searchQuery));
		$fields = $this->getParameter('book_fields_short');
		if ($searchQuery->field && !in_array($searchQuery->field, $fields)) {
			// include the search field in the book output
			$fields[] = $searchQuery->field;
		}
		return $this->renderBookListing('Book/index.html.twig', $pager, [
			'fields' => $fields,
			'query' => $searchQuery,
		]);
	}

	/**
	 * @Route("/categories", name="books_categories")
	 */
	public function listCategoriesAction() {
		return $this->render('Book/listCategories.html.twig', [
			'tree' => $this->generateCategoryTree(),
		]);
	}

	/**
	 * @Route("/categories/{slug}", name="books_by_category")
	 */
	public function listByCategoryAction(Request $request, BookCategory $category) {
		$pager = $this->pager($request, $this->repoFinder()->forBook()->filterByCategory($category));
		return $this->renderBookListing('Book/listByCategory.html.twig', $pager, [
			'category' => $category,
			'categoryPath' => $this->repoFinder()->forBookCategory()->getPath($category),
			'tree' => $this->generateCategoryTree($category),
		]);
	}

	/**
	 * @Route("/incomplete", name="books_incomplete")
	 */
	public function listIncompleteAction(Request $request) {
		$pager = $this->pager($request, $this->repoFinder()->forBook()->filterIncomplete());
		return $this->renderBookListing('Book/listIncomplete.html.twig', $pager);
	}

	/**
	 * @Route("/search-duplicates", name="books_search_duplicates")
	 */
	public function searchDuplicatesAction(Request $request) {
		$books = $this->repoFinder()->forBook()->findDuplicatesByTitle($request->query->get('title'), $request->query->get('id'));
		return $this->render('Book/searchDuplicates.html.twig', [
			'books' => $books,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/revisions", name="books_revisions")
	 */
	public function showAllRevisionsAction(Request $request) {
		$pager = $this->pager($request, $this->repoFinder()->forBook()->revisions(), 30);
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
		]);
	}

	/**
	 * @Route("/{id}.{_format}", defaults={"_format" = "html"}, name="books_show")
	 */
	public function showAction(Book $book, $_format) {
		if ($_format == 'cover') {
			return $this->redirect(Thumbnail::createPath($book->getCover(), 'covers', 300));
		}
		return $this->render('Book/show.html.twig', [
			'book' => $book,
			'fields' => $this->getParameter('book_fields_long'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'addToShelfForms' => $this->createAddToShelfForms([$book]),
		]);
	}

	private function renderBookListing($template, Pagerfanta $pager, $viewVariables = []) {
		return $this->render($template, array_merge([
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookQuery::getSearchableFieldsDefinition(),
			'sortableFields' => BookQuery::$sortableFields,
			'addToShelfForms' => $this->createAddToShelfForms($pager->getCurrentPageResults()),
		], $viewVariables));
	}

	/**
	 * @param BookCategory $rootCategory
	 * @return string
	 */
	private function generateCategoryTree($rootCategory = null) {
		return $this->repoFinder()->forBookCategory()->childrenHierarchy($rootCategory, false /* false: load only direct children */, $this->categoryTreeOptions());
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
