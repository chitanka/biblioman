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
		return $this->render('Main/index.html.twig', [
			'recentBooks' => $recentBooks,
		]);
	}
}
