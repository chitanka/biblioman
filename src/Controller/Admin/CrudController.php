<?php namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

abstract class CrudController extends AbstractCrudController {

	/** @param FieldTrait[] $fields */
	protected function renderFieldsWithFullsize(iterable $fields) {
		$columnSize = 12;
		foreach ($fields as $field) {
			$field->setColumns($columnSize);
		}
	}
}
