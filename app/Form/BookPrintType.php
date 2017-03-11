<?php namespace App\Form;

use App\Entity\BookPrint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookPrintType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('printingHouse');
		$builder->add('typeSettingIn');
		$builder->add('printSigned');
		$builder->add('printOut');
		$builder->add('printerSheets');
		$builder->add('publisherSheets');
		$builder->add('provisionPublisherSheets');
		$builder->add('totalPrint');
	}

	public function getBlockPrefix() {
		return 'book_print';
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => BookPrint::class,
		));
	}
}
