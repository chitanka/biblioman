<?php namespace App\Twig;

use App\Entity\Entity;
use App\File\Normalizer;
use App\File\Path;
use App\File\Thumbnail;
use Doctrine\Common\Collections\ArrayCollection;
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
			new \Twig_SimpleFilter('format_bytes', [$this, 'formatBytes']),
			new \Twig_SimpleFilter('maxlength', [$this, 'maxlength'], ['is_safe' => ['html']]),
			new \Twig_SimpleFilter('thumb', [$this, 'createThumbPath']),
			new \Twig_SimpleFilter('contentpath', [$this, 'createContentPath']),
			new \Twig_SimpleFilter('ids', [$this, 'getIdsFromCollection']),
		];
	}

	public function autolink($content) {
		$content = preg_replace_callback('/([Зз]апис|[Кк]нига|[Нн]омер|#|№) ?(\d+)/u', function($matches) {
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

	// from https://stackoverflow.com/a/28047922
	public function formatBytes($bytes, $precision = 2) {
		if ($bytes === null) {
			return '';
		}
		$units = ['B', 'KiB', 'MiB', 'GiB'];
		$unitIndex = (int) floor(log($bytes, 1024));
		return round($bytes/pow(1024, $unitIndex), $precision) .' '. $units[$unitIndex];
	}

	public function maxlength($string, $maxlength = 30, $suffix = null) {
		$result = mb_substr($string, 0, $maxlength);
		if (mb_strlen($string) > $maxlength) {
			$result .= $suffix ?: '…';
		}
		return $result;
	}

	public function createThumbPath($image, $type, $width, $humanReadableName = null) {
		return Thumbnail::createPath($image, $type, $width, $humanReadableName);
	}

	public function createContentPath($file, $title) {
		return implode('/', array_filter([Path::DIR_FULLCONTENT, Thumbnail::createSubPathFromFileName($file), $file, Thumbnail::normalizeHumanReadableNameForFile($title, $file)]));
	}

	public function getIdsFromCollection(ArrayCollection $collection) {
		return $collection->map(function(Entity $entity) {
			// a string is needed for the option value comparison in twig
			/* @see twig_is_selected_choice() */
			return (string) $entity->getId();
		})->toArray();
	}

}
