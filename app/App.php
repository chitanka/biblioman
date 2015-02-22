<?php namespace App;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class App extends Bundle {

	protected $name = 'App';

	public function getNamespace() {
		return 'App';
	}
}
