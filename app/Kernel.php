<?php namespace App;

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel {

	const ENV_PRODUCTION = 'prod';
	const ENV_DEVELOPMENT = 'dev';
	const ENV_TEST = 'test';

	protected $rootDir = __DIR__;

	public function registerBundles() {
		switch ($this->environment) {
			case self::ENV_PRODUCTION:
				return $this->getBundlesForProduction();
			default:
				return $this->getBundlesForDevelopment();
		}
	}

	protected function getBundlesForProduction() {
		return [
			new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new \Symfony\Bundle\TwigBundle\TwigBundle(),
			new \Symfony\Bundle\MonologBundle\MonologBundle(),
			new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new \JavierEguiluz\Bundle\EasyAdminBundle\EasyAdminBundle(),
			new \Vich\UploaderBundle\VichUploaderBundle(),
			new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
			new \FOS\MessageBundle\FOSMessageBundle(),
			new \Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
			new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new \Chitanka\WikiBundle\ChitankaWikiBundle(),
			new App(),
		];
	}

	protected function getBundlesForDevelopment() {
		return array_merge($this->getBundlesForProduction(), [
			new \Symfony\Bundle\DebugBundle\DebugBundle(),
			new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
			new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
		]);
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
