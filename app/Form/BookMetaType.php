<?php namespace App\Form;

use App\Entity\BookMeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookMetaType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('notes');
		$builder->add('infoSources');
		$builder->add('adminComment');
		$builder->add('ocredText');
		$builder->add('isIncomplete');
		$builder->add('reasonWhyIncomplete');
	}

	public function getBlockPrefix() {
		return 'book_meta';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookMeta::class,
		));
	}
}
