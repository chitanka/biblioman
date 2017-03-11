<?php namespace App\Form;

use App\Entity\BookAuthorship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookAuthorshipType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('author');
		$builder->add('translator');
		$builder->add('translatedFromLanguage');
		$builder->add('dateOfTranslation');
		$builder->add('adaptedBy');
		$builder->add('otherAuthors');
		$builder->add('compiler');
	}

	public function getBlockPrefix() {
		return 'book_authorship';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookAuthorship::class,
		));
	}
}
