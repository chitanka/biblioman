<?php namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class AdminController extends EasyAdminController {

	/**
	 * @Route("/admin/", name="admin")
	 */
	public function indexAction(Request $request) {
		return parent::indexAction($request);
	}

	protected function prepareNewEntityForPersist($entity) {
		if ($entity instanceof Book) {
			$user = $this->get('security.context')->getToken()->getUser();
			return $entity->setCreatedBy($user->getUsername());
		}
	}

}
