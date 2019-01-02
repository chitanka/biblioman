<?php namespace App\Controller;

use App\Entity\User;
use App\Http\Request;
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

	/**
	 * @Route("/{username}", name="users_show")
	 */
	public function show(User $user) {
		return $this->render('User/show.html.twig', ['user' => $user]);
	}

	/**
	 * @Route("/{username}/created-books", name="users_show_created_books")
	 */
	public function showCreatedBooks(Request $request, User $user) {
		return $this->redirectToRoute('books', ['q' => 'createdBy: '.$user->getName()]);
	}

	/**
	 * @Route("/{username}/completed-books", name="users_show_completed_books")
	 */
	public function showCompletedBooks(Request $request, User $user) {
		return $this->redirectToRoute('books', ['q' => 'completedBy: '.$user->getName()]);
	}

	/**
	 * @Route("/{username}/book-revisions", name="users_show_book_revisions")
	 */
	public function showBookRevisions(Request $request, User $user) {
		$pager = $this->pager($request, $this->repoFinder()->forBook()->revisionsFromUser($user), 30);
		return $this->render('Book/showAllRevisions.html.twig', [
			'pager' => $pager,
		]);
	}
}
