<?php namespace App\Form;

use App\Entity\BookContent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookContentType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('contentType');
		$builder->add('nationality');
		$builder->add('language');
		$builder->add('notesAboutOriginal');
		$builder->add('annotation');
		$builder->add('notesAboutAuthor');
		$builder->add('marketingSnippets');
		$builder->add('toc');
	}

	public function getBlockPrefix() {
		return 'book_content';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookContent::class,
		));
	}
}
