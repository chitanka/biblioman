<?php namespace App\Form;

use App\Entity\BookLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookLinkType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('url');
		$builder->add('category', ChoiceType::class, [
			'choices' => array_combine(BookLink::$categories, BookLink::$categories),
			'choice_translation_domain' => true,
			'choice_label' => function($value, $key, $index) {
				return 'BookLinkCategory.'.$key;
			},
		]);
		$builder->add('title');
		$builder->add('author');
	}

	public function getBlockPrefix() {
		return 'book_link';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookLink::class,
		));
	}
}
