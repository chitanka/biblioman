<?php namespace App\File;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\Polyfill\FileExtensionTrait;

class Namer implements \Vich\UploaderBundle\Naming\NamerInterface {

	use FileExtensionTrait;

	/**
	 * Creates a name for the file being uploaded.
	 *
	 * @param object $object The object the upload is attached to.
	 * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
	 *
	 * @return string The file name.
	 */
	public function name($object, PropertyMapping $mapping) {
		$file = $mapping->getFile($object);
		$name = uniqid();

		if ($extension = $this->getExtension($file)) {
			$extension = strtolower($extension);
			if (in_array($extension, ['tif', 'tiff'])) {
				$pathname = $file->getPathname();
				shell_exec("convert $pathname $pathname.jpg && mv $pathname.jpg $pathname");
			}
			$extension = preg_replace('/(tiff?|jpeg)/', 'jpg', $extension);
			$name = sprintf('%s.%s', $name, $extension);
		}

		return $name;
	}
}
