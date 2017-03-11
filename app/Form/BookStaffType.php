<?php namespace App\Form;

use App\Entity\BookStaff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookStaffType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('chiefEditor');
		$builder->add('managingEditor');
		$builder->add('editor');
		$builder->add('editorialStaff');
		$builder->add('publisherEditor');
		$builder->add('artistEditor');
		$builder->add('technicalEditor');
		$builder->add('consultant');
		$builder->add('scienceEditor');
		$builder->add('copyreader');
		$builder->add('reviewer');
		$builder->add('artist');
		$builder->add('illustrator');
		$builder->add('corrector');
		$builder->add('layout');
		$builder->add('coverLayout');
		$builder->add('libraryDesign');
		$builder->add('computerProcessing');
		$builder->add('prepress');
	}

	public function getBlockPrefix() {
		return 'book_staff';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookStaff::class,
		));
	}
}
