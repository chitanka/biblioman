<?php namespace App\Form;

use App\Entity\BookBody;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookBodyType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('format');
		$builder->add('pageCount');
		$builder->add('binding');
	}

	public function getBlockPrefix() {
		return 'book_body';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookBody::class,
		));
	}
}
