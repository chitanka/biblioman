<?php namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase {

	public function testIndex() {
		$client = static::createClient();

		$crawler = $client->request('GET', '/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($crawler->filter('html:contains("Здравей")')->count() > 0);
	}
}
