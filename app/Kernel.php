<?php namespace App;

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel {

	const ENV_PRODUCTION = 'prod';
	const ENV_DEVELOPMENT = 'dev';
	const ENV_TEST = 'test';

	protected $rootDir = __DIR__;

	public function registerBundles() {
		$bundles = require __DIR__."/config/bundles.php";
		if ($this->environment == self::ENV_PRODUCTION) {
			return $bundles;
		}
		return array_merge($bundles, require __DIR__."/config/bundles_dev.php");
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load("$this->rootDir/config/config_$this->environment.yml");
	}

	public function getCacheDir() {
		return "$this->rootDir/../var/cache/$this->environment";
	}

	public function getLogDir() {
		return "$this->rootDir/../var/log";
	}
}
