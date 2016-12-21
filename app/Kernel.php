<?php namespace App;

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel {

    protected $rootDir = __DIR__;

	public function registerBundles() {
		$bundles = array(
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
			new App(),
		);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
		}

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
	}

	/** {@inheritdoc} */
	public function getCacheDir() {
		return __DIR__.'/../var/cache/'.$this->environment;
	}

	/** {@inheritdoc} */
	public function getLogDir() {
		return __DIR__.'/../var/log';
	}
}
