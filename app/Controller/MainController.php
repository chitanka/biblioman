<?php namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
		$recentBooks = $this->getDoctrine()->getManager()
			->getRepository('App:Book')
			->findRecent(15);
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
		];
		return $this->render('Main/index.html.twig', [
			'recentBooks' => $recentBooks,
			'fields' => $fields,
		]);
	}
}
