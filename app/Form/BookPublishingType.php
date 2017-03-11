<?php namespace App\Form;

use App\Entity\BookPublishing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookPublishingType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('edition');
		$builder->add('publisher');
		$builder->add('publisherCity');
		$builder->add('publishingYear');
		$builder->add('publisherAddress');
		$builder->add('publisherCode');
		$builder->add('publisherOrder');
		$builder->add('publisherNumber');
		$builder->add('price');
	}

	public function getBlockPrefix() {
		return 'book_publishing';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookPublishing::class,
		));
	}
}
