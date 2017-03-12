<?php namespace App\Form;

use App\Entity\BookClassification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookClassificationType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('themes');
		$builder->add('genre');
		$builder->add('trackingCode');
		$builder->add('litGroup');
		$builder->add('uniformProductClassification');
		$builder->add('universalDecimalClassification');
	}

	public function getBlockPrefix() {
		return 'book_classification';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookClassification::class,
		));
	}
}
