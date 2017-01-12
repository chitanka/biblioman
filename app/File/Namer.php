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
		$extension = $this->fixExtension($mapping->getFile($object));
		return uniqid().'.'.$extension;
	}

	private function fixExtension(\Symfony\Component\HttpFoundation\File\UploadedFile $file) {
		$extension = strtolower($this->getExtension($file));
		if (in_array($extension, ['tif', 'tiff'])) {
			$pathname = $file->getPathname();
			shell_exec("convert $pathname $pathname.png && mv $pathname.png $pathname");
		}
		$extension = strtr($extension, [
			'tiff' => 'png',
			'tif' => 'png',
			'jpeg' => 'jpg',
		]);
		return $extension;
	}
}
