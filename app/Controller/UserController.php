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
	 * @Route("/by-role/{role}", name="users_by_role", defaults={"role": ""})
	 */
	public function byRole($role) {
		$roles = $role ? [User::normalizeRoleName($role)] : User::ROLES;
		$users = $this->repoFinder()->forUser()->findUsersWithRole($role);
		return $this->render('User/byRole.html.twig', [
			'roles' => $roles,
			'users' => $users,
		]);
	}

}
