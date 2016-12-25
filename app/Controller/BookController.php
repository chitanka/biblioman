<?php namespace App\Controller;

use App\Entity\BookCategory;
use App\Entity\BookRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/books")
 */
class BookController extends Controller {

	const ITEMS_PER_PAGE = 24;

	/**
	 * @Route("/", name="books")
	 */
	public function indexAction(Request $request) {
		$searchQuery = BookRepository::getStructuredSearchQuery($request->query->get('q'));
		$adapter = new DoctrineORMAdapter($this->repo()->filterByQuery($searchQuery->raw));
		$pager = $this->pager($request, $adapter);
		$fields = $this->getParameter('book_fields_short');
		if ($searchQuery->field && !in_array($searchQuery->field, $fields)) {
			// include the search field in the book output
			$fields[] = $searchQuery->field;
		}
		return $this->render('Book/index.html.twig', [
			'pager' => $pager,
			'fields' => $fields,
			'searchableFields' => BookRepository::getSearchableFieldsDefinition(),
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
		$adapter = new DoctrineORMAdapter($this->repo()->filterByCategory($category));
		$pager = $this->pager($request, $adapter);
		return $this->render('Book/listByCategory.html.twig', [
			'category' => $category,
			'categoryPath' => $this->repo()->getCategoryRepository()->getPath($category),
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

	private function generateCategoryTree($rootCategory = null) {
		return $this->repo()->getCategoryRepository()->childrenHierarchy($rootCategory, false /* false: load only direct children */, $this->categoryTreeOptions());
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
