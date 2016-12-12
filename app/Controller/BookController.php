<?php namespace App\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
		$page = $request->query->get('page', 1);
		$maxResults = 15;
		$queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()
			->select('b')
			->from('App:Book', 'b');
		$adapter = new DoctrineORMAdapter($queryBuilder);
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxResults);
		$pager->setCurrentPage($page);
		$fields = [
			'author',
			'title',
			'subtitle',
			'sequence',
			'chiefEditor',
			'editor',
			'publisherEditor',
			'translator',
			'compiler',
			'artist',
			'artistEditor',
			'technicalEditor',
			'publisher',
			'pubDate',
			'nationality',
			'edition',
			'genre',
			'format',
			'isbn10',
			'isbn13',
			'createdBy',
		];
		return $this->render('Book/index.html.twig', [
			'pager' => $pager,
			'fields' => $fields,
		]);
	}

		/**
	 * @Route("/{id}", name="books_show")
	 */
	public function showAction($id) {
		$book = $this->getDoctrine()->getManager()
			->getRepository('App:Book')
			->find($id);
		if (!$book) {
			throw $this->createNotFoundException('Book not found');
		}
		$fields = [
			'chitankaId',
			'author',
			'title',
			'subtitle',
			'sequence',
			'sequenceNr',
			'translator',
			'translatedFromLanguage',
			'dateOfTranslation',
			'compiler',
			'editorialStaff',
			'chiefEditor',
			'editor',
			'publisherEditor',
			'consultant',
			'artist',
			'artistEditor',
			'technicalEditor',
			'reviewer',
			'corrector',
			'layout',
			'coverLayout',
			'computerProcessing',
			'prepress',
			'publisher',
			'pubCity',
			'pubDate',
			'publisherAddress',
			'print',
			'contentType',
			'nationality',
			'language',
			'edition',
			'litGroup',
			'typeSettingIn',
			'printSigned',
			'printOut',
			'printerSheets',
			'publisherSheets',
			'provisionPublisherSheets',
			'format',
			'publisherCode',
			'trackingCode',
			'publisherOrder',
			'publisherNumber',
			'uniformProductClassification',
			'pageCount',
			'totalPrint',
			'price',
			'binding',
			'illustrated',
			'isbn10',
			'isbn13',
			'genre',
			'themes',
			'annotation',
			'marketingSnippets',
			'toc',
			'notes',
			'notesAboutOriginal',
			'createdBy',
			#'createdAt',
			#'updatedAt',
		];
		return $this->render('Book/show.html.twig', [
			'book' => $book,
			'fields' => $fields,
		]);
	}
}
