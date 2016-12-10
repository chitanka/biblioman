<?php namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/books")
 */
class BookController extends Controller {

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
