<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return Person::class;
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setPageTitle(Crud::PAGE_INDEX, 'Persons')
			->setSearchFields(['name', 'nameType', 'id']);
	}

	public function configureFields(string $pageName): iterable
	{
		$name = TextField::new('name');
		$nameType = TextField::new('nameType')->setTemplatePath('admin/Person/nameType.html.twig');
		$canonicalPerson = AssociationField::new('canonicalPerson');
		$id = IntegerField::new('id', 'ID');
		$relatedPersons = AssociationField::new('relatedPersons');

		if (Crud::PAGE_INDEX === $pageName) {
			return [$name, $nameType, $canonicalPerson, $relatedPersons];
		}
		if (Crud::PAGE_DETAIL === $pageName) {
			return [$name, $nameType, $id, $canonicalPerson, $relatedPersons];
		}
		if (Crud::PAGE_NEW === $pageName) {
			return [$name, $nameType, $canonicalPerson];
		}
		if (Crud::PAGE_EDIT === $pageName) {
			return [$name, $nameType, $canonicalPerson];
		}
	}
}
