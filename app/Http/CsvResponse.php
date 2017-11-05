<?php namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response {

	public function __construct($content = '', $status = 200, array $headers = array()) {
		parent::__construct($content, $status, $headers);
		if (!isset($headers['Content-Type'])) {
			$this->headers->set('Content-Type', 'text/csv');
		}
	}
}
