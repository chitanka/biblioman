<?php namespace App\Twig;

use Symfony\Component\Routing\Router;

class Extension extends \Twig_Extension {

	private $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

	public function getFilters() {
		return [
			new \Twig_SimpleFilter('autolink', [$this, 'autolink'], ['is_safe' => ['html']]),
			new \Twig_SimpleFilter('format_whitespaces', [$this, 'formatWhitespaces'], ['is_safe' => ['html']]),
			new \Twig_SimpleFilter('thumb', [$this, 'createThumbPath']),
		];
	}

	public function autolink($content) {
		$content = preg_replace_callback('/(запис|книга|номер|#|№) ?(\d+)/', function($matches) {
			$url = $this->router->generate('books_show', ['id' => $matches[2]]);
			return '<a href="'.$url.'">'.$matches[0].'</a>';
		}, $content);
		return $content;
	}

	public function formatWhitespaces($content) {
		$content = nl2br($content);
		$content = strtr($content, [
			"\t" => str_repeat("\xC2\xA0", 8), // non-breaking space
		]);
		return $content;
	}

	public function createThumbPath($image, $type, $width) {
		return "/thumb/$type/" . preg_replace('/\.(.+)$/', ".$width.$1", $image);
	}

	public function getName() {
		return 'app_extension';
	}
}
