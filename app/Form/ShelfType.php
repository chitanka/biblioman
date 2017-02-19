<?php namespace App\Form;

use App\Entity\Shelf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShelfType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name');
		$builder->add('icon');
		$builder->add('description');
		$builder->add('isPublic');
		$builder->add('save', SubmitType::class);
	}

	public function getBlockPrefix() {
		return 'shelf';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => Shelf::class,
		));
	}
}
