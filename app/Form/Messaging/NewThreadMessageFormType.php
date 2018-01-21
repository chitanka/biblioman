<?php namespace App\Form\Messaging;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class NewThreadMessageFormType extends \FOS\MessageBundle\FormType\NewThreadMessageFormType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('recipient', \App\Form\UsernameFormType::class, array(
			'label' => 'recipient',
			'translation_domain' => 'FOSMessageBundle',
			'usernameTransformer' => $options['usernameTransformer'],
		));
		$builder->add('subject', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
			'label' => 'subject',
			'translation_domain' => 'FOSMessageBundle',
		));
		$builder->add('body', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, array(
			'label' => 'body',
			'translation_domain' => 'FOSMessageBundle',
			'attr' => ['class' => 'message-body'],
		));
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'usernameTransformer' => null,
		));
	}
}
