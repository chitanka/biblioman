<?php namespace App\Form;

use App\Entity\BookOnShelf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookOnShelfType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('book');
		$builder->add('shelf');
	}

	public function getBlockPrefix() {
		return 'book_on_shelf';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookOnShelf::class,
		));
	}
}

