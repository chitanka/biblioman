<?php namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class AdminController extends EasyAdminController {

	/**
	 * @Route("/admin/", name="easyadmin")
	 */
	public function indexAction(Request $request) {
		return parent::indexAction($request);
	}

	protected function prePersistBookEntity(Book $book) {
		$user = $this->get('security.token_storage')->getToken()->getUser();
		return $book->setCreatedBy($user->getUsername());
	}

}
