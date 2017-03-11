<?php namespace App\Form;

use App\Entity\BookGrouping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookGroupingType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('sequence');
		$builder->add('sequenceNr');
		$builder->add('subsequence');
		$builder->add('subsequenceNr');
		$builder->add('series');
		$builder->add('seriesNr');
	}

	public function getBlockPrefix() {
		return 'book_grouping';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookGrouping::class,
		));
	}
}
