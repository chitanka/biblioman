<?php namespace App\Form;

use App\Entity\BookContentFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class BookContentFileType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('file', VichFileType::class);
		$builder->add('title');
	}

	public function getBlockPrefix() {
		return 'book_content_file';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookContentFile::class,
		));
	}
}
