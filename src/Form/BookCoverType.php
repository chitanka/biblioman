<?php namespace App\Form;

use App\Entity\BookCover;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookCoverType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('file', VichImageType::class);
		$builder->add('type', ChoiceType::class, [
			'choices' => \App\Entity\BookCoverType::getValuesAsAssocArray(),
			'choice_label' => function($value, $key) { return 'BookCover.'.$key; },
		]);
		$builder->add('title');
	}

	public function getBlockPrefix() {
		return 'book_cover';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookCover::class,
		));
	}
}
