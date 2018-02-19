<?php namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/users")
 */
class UserController extends Controller {

	/**
	 * @Route("/", name="users")
	 */
	public function index() {
		return $this->render('User/index.html.twig');
	}

	/**
	 * @Route("/by-role", name="users_by_role")
	 */
	public function byRole() {
		$users = $this->repoFinder()->forUser()->findUsersWithExtraRoles();
		return $this->render('User/byRole.html.twig', [
			'roles' => User::ROLES,
			'users' => $users,
		]);
	}

}
