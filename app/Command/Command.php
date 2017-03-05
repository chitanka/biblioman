<?php namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends ContainerAwareCommand {

	protected function configure() {
		$this->setName($this->getName());
		$this->setDescription($this->getDescription());
		$this->setHelp($this->getHelp());
		$this->doWithEveryElement($this->getRequiredArguments(), function($argument, $description) {
			$this->addArgument($argument, InputArgument::REQUIRED, $description);
		});
		$this->doWithEveryElement($this->getOptionalArguments(), function($argument, $descriptionAndValue) {
			list($description, $defaultValue) = $descriptionAndValue;
			$this->addArgument($argument, InputArgument::OPTIONAL, $description, $defaultValue);
		});
		$this->doWithEveryElement($this->getArrayArguments(), function($argument, $description) {
			$this->addArgument($argument, InputArgument::IS_ARRAY, $description);
		});
		$this->doWithEveryElement($this->getBooleanOptions(), function($option, $description) {
			$this->addOption($option, null, InputOption::VALUE_NONE, $description);
		});
		$this->doWithEveryElement($this->getOptionalOptions(), function($option, $descriptionAndValue) {
			list($description, $defaultValue) = $descriptionAndValue;
			$this->addOption($option, null, InputOption::VALUE_OPTIONAL, $description, $defaultValue);
		});
	}

	/**
	 * Return an array with all required arguments.
	 * Format is:
	 *     argument name => argument description
	 * @return array
	 */
	protected function getRequiredArguments() {
		return [];
	}

	/**
	 * Return an array with all optional arguments.
	 * Format is:
	 *     argument name => [argument description, default value]
	 * @return array
	 */
	protected function getOptionalArguments() {
		return [];
	}

	/**
	 * Return an array with all arguments which values are arrays.
	 * Format is:
	 *     argument name => argument description
	 * @return array
	 */
	protected function getArrayArguments() {
		return [];
	}

	/**
	 * Return an array with all boolean options.
	 * Format is:
	 *     option name => option description
	 * @return array
	 */
	protected function getBooleanOptions() {
		return [];
	}

	/**
	 * Return an array with all optional options.
	 * Format is:
	 *     option name => [option description, default value]
	 * @return array
	 */
	protected function getOptionalOptions() {
		return [];
	}

	/**
	 * Override only to replace return value in the docblock
	 * @return \Symfony\Bundle\FrameworkBundle\Console\Application
	 */
	public function getApplication() {
		return parent::getApplication();
	}

	protected function getKernel() {
		return $this->getApplication()->getKernel();
	}

	protected function executeUpdates($updates, \Doctrine\DBAL\Connection $connection) {
		$connection->beginTransaction();
		foreach ($updates as $update) {
			$connection->executeUpdate($update);
		}
		$connection->commit();
	}

	/** @return EntityManager */
	protected function getEntityManager() {
		return $this->getContainer()->get('doctrine')->getManager();
	}

	/**
	 * @param array|\Traversable $values
	 * @param callable $callback
	 */
	private function doWithEveryElement($values, $callback) {
		foreach ($values as $key => $value) {
			$callback($key, $value);
		}
	}
}
