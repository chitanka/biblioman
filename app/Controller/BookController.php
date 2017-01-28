<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/books")
 */
class BookController extends Controller {

	/**
	 * @Route("/", name="books")
	 */
	public function indexAction(Request $request) {
		$searchQuery = BookRepository::getStructuredSearchQuery($request->query->get('q'));
		$query = $this->bookRepo()->filterByQuery($searchQuery->raw, $request->query->get('sort'));
		$pager = $this->pager($request, $query);
		$fields = $this->getParameter('book_fields_short');
		if ($searchQuery->field && !in_array($searchQuery->field, $fields)) {
			// include the search field in the book output
			$fields[] = $searchQuery->field;
		}
		return $this->render('Book/index.html.twig', [
			'pager' => $pager,
			'fields' => $fields,
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
			'sortableFields' => BookRepository::$sortableFields,
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
		$pager = $this->pager($request, $this->bookRepo()->filterByCategory($category));
		return $this->render('Book/listByCategory.html.twig', [
			'category' => $category,
			'categoryPath' => $this->bookRepo()->getCategoryRepository()->getPath($category),
			'tree' => $this->generateCategoryTree($category),
			'pager' => $pager,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/incomplete", name="books_incomplete")
	 */
	public function listIncompleteAction(Request $request) {
		$pager = $this->pager($request, $this->bookRepo()->filterIncomplete());
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
		$books = $this->bookRepo()->findDuplicatesByTitle($request->query->get('title'), $request->query->get('id'));
		return $this->render('Book/searchDuplicates.html.twig', [
			'books' => $books,
			'fields' => $this->getParameter('book_fields_short'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	/**
	 * @Route("/revisions", name="books_revisions")
	 */
	public function showAllRevisionsAction(Request $request) {
		$pager = $this->pager($request, $this->bookRepo()->revisions(), 30);
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
	 * @Route("/{id}", name="books_show")
	 */
	public function showAction(Book $book) {
		return $this->render('Book/show.html.twig', [
			'book' => $book,
			'fields' => $this->getParameter('book_fields_long'),
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
		]);
	}

	private function generateCategoryTree($rootCategory = null) {
		return $this->bookRepo()->getCategoryRepository()->childrenHierarchy($rootCategory, false /* false: load only direct children */, $this->categoryTreeOptions());
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
