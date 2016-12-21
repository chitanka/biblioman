<?php namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsernameFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->addModelTransformer($options['usernameTransformer']);
	}

	public function getParent() {
		return \Symfony\Component\Form\Extension\Core\Type\TextType::class;
	}

	public function getBlockPrefix() {
		return 'user_username';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'usernameTransformer' => null,
		));
	}
}
