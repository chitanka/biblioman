<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return User::class;
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setEntityLabelInSingular('User')
			->setEntityLabelInPlural('User')
			->setSearchFields(['username', 'email', 'roles', 'id']);
	}

	public function configureActions(Actions $actions): Actions
	{
		return $actions
			->disable('new', 'delete');
	}

	public function configureFields(string $pageName): iterable
	{
		$username = TextField::new('username');
		$email = TextField::new('email');
		$roles = ArrayField::new('roles');
		$lastLogin = DateTimeField::new('lastLogin');
		$preferences = TextField::new('preferences');
		$id = IntegerField::new('id', 'ID');
		$shelves = AssociationField::new('shelves');

		if (Crud::PAGE_INDEX === $pageName) {
			return [$username, $email, $lastLogin, $id, $shelves];
		} elseif (Crud::PAGE_DETAIL === $pageName) {
			return [$username, $email, $lastLogin, $roles, $preferences, $id, $shelves];
		} elseif (Crud::PAGE_NEW === $pageName) {
			return [$username, $email, $roles];
		} elseif (Crud::PAGE_EDIT === $pageName) {
			return [$username, $email, $roles];
		}
	}
}
