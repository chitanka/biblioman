<?php namespace App\Form;

use App\Entity\BookTitling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookTitlingType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('title');
		$builder->add('altTitle');
		$builder->add('subtitle');
		$builder->add('subtitle2');
		$builder->add('volumeTitle');
	}

	public function getBlockPrefix() {
		return 'book_titling';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookTitling::class,
		));
	}
}
