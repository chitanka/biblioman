<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Person;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController {
	/**
	 * @Route("/admin")
	 */
	public function index(): \Symfony\Component\HttpFoundation\Response {
		$routeBuilder = $this->get(\EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator::class)->build();
		return $this->redirect($routeBuilder->setController(BookCrudController::class)->generateUrl());
	}

	public function configureDashboard(): Dashboard {
		return Dashboard::new()
			->setTitle('<img src="/images/logo.png" width="36" alt="Лого"> Библиоман')
		;
	}

	public function configureCrud(): Crud {
		return Crud::new();
	}

	public function configureAssets(): Assets {
		return Assets::new()
			->addHtmlContentToHead('<style>:root { --color-primary: #2B547E; }</style>')
			->addCssFile('//cdn.rawgit.com/noelboss/featherlight/1.2.0/release/featherlight.min.css')
			->addCssFile('css/admin.css')
			->addJsFile('//code.jquery.com/jquery-3.5.1.min.js')
			->addJsFile('//cdn.rawgit.com/noelboss/featherlight/1.2.0/release/featherlight.min.js')
			->addJsFile('js/admin.js')
		;
	}

	public function configureMenuItems(): iterable {
		yield MenuItem::linktoRoute('Main page', 'fas fa-home', 'homepage');
		yield MenuItem::linkToCrud('Books', 'fas fa-book', Book::class)
			->setPermission(User::ROLE_EDITOR)
		;
		yield MenuItem::linkToCrud('Persons', 'fas fa-user-edit', Person::class)->setPermission(User::ROLE_EDITOR_SENIOR);
		yield MenuItem::section('Administration')->setPermission(User::ROLE_ADMIN);
		yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)->setPermission(User::ROLE_ADMIN);
	}
}
