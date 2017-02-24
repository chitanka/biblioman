<?php namespace App\Form\Messaging;

use App\Form\DataTransformer\UserToUsernameTransformer;
use Symfony\Component\Form\FormFactoryInterface;

class NewThreadMessageFormFactory extends \FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory {

	/**
	 * @var UserToUsernameTransformer
	 */
	private $usernameTransformer;

	/**
	 * @param UserToUsernameTransformer $usernameTransformer
	 */
	public function __construct(FormFactoryInterface $formFactory, $formType, $formName, $messageClass, UserToUsernameTransformer $usernameTransformer) {
		parent::__construct($formFactory, $formType, $formName, $messageClass);
		$this->usernameTransformer = $usernameTransformer;
	}

	/**
	 * @see \Symfony\Component\Form\FormFactory::createNamedBuilder
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function create() {
		return $this->formFactory->createNamed($this->formName, get_class($this->formType), $this->createModelInstance(), [
			'usernameTransformer' => $this->usernameTransformer,
		]);
	}
}
