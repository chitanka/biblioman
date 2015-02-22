<?php namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
		$p = new \App\Entity\Sequence;
		return $this->render('Main/index.html.twig');
	}
}
