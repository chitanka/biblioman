<?php namespace App\Form;

use App\Entity\BookScan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookScanType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('file', VichImageType::class);
		$builder->add('title');
	}

	public function getBlockPrefix() {
		return 'book_scan';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookScan::class,
		));
	}
}
