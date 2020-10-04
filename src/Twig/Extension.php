<?php namespace App\Twig;

use App\Entity\Entity;
use App\File\Normalizer;
use App\File\Path;
use App\File\Thumbnail;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Routing\Router;
use Twig\TwigFilter;

class Extension extends \Twig\Extension\AbstractExtension {

	private $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

	public function getFilters() {
		return [
			new TwigFilter('converturls', [$this, 'convertUrls'], ['is_safe' => ['html']]),
			new TwigFilter('autolink', [$this, 'autolink'], ['is_safe' => ['html']]),
			new TwigFilter('format_paragraphs', [$this, 'formatParagraphs'], ['is_safe' => ['html']]),
			new TwigFilter('format_whitespaces', [$this, 'formatWhitespaces'], ['is_safe' => ['html']]),
			new TwigFilter('format_bytes', [$this, 'formatBytes']),
			new TwigFilter('maxlength', [$this, 'maxlength'], ['is_safe' => ['html']]),
			new TwigFilter('thumb', [$this, 'createThumbPath']),
			new TwigFilter('contentpath', [$this, 'createContentPath']),
			new TwigFilter('ids', [$this, 'getIdsFromCollection']),
			new TwigFilter('admin_title', [$this, 'formatTitleLikeAdmin']),
		];
	}

	public function convertUrls(string $content) {
		return $content;
	}

	public function autolink($content) {
		$content = preg_replace_callback('/([Зз]апис|[Кк]нига|[Нн]омер|#|№) ?(\d+)/u', function($matches) {
			$url = $this->router->generate('books_show', ['id' => $matches[2]]);
			return '<a href="'.$url.'">'.$matches[0].'</a>';
		}, $content);
		return $content;
	}

	public function formatParagraphs($text): string {
		if (empty($text)) {
			return '';
		}
		return '<div class="text-content"><p>'.strtr($text, ["\n" => '</p><p>', "\r" => '']).'</p></div>';
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

	public function getIdsFromCollection(Collection $collection) {
		return $collection->map(function(Entity $entity) {
			// a string is needed for the option value comparison in twig
			/* @see twig_is_selected_choice() */
			return (string) $entity->getId();
		})->toArray();
	}

	public function formatTitleLikeAdmin(string $fieldName): string {
		return ucwords(\Symfony\Component\String\u($fieldName)->snake()->replace('_', ' '));
	}
}
