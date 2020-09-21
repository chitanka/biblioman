<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Person;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
	/**
	 * @Route("/admin", name="easyadmin")
	 */
	public function index(): \Symfony\Component\HttpFoundation\Response
	{
		$routeBuilder = $this->get(\EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator::class)->build();
		return $this->redirect($routeBuilder->setController(BookCrudController::class)->generateUrl());
	}

	public function configureDashboard(): Dashboard
	{
		return Dashboard::new()
			->setTitle('Библиоман');
	}

	public function configureCrud(): Crud
	{
		return Crud::new();
	}

	public function configureMenuItems(): iterable
	{
		yield MenuItem::linktoRoute('Main page', 'fas fa-folder-open', 'homepage');
		yield MenuItem::linkToCrud('Books', 'fas fa-folder-open', Book::class);
		yield MenuItem::linkToCrud('Persons', 'fas fa-folder-open', Person::class);
	}
}
