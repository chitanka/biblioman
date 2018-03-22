<?php namespace App\File;

use App\Entity\Book;
use App\Entity\BookFile;
use App\Entity\Entity;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\Polyfill\FileExtensionTrait;

class Namer implements \Vich\UploaderBundle\Naming\NamerInterface, \Vich\UploaderBundle\Naming\DirectoryNamerInterface {

	use FileExtensionTrait;

	/**
	 * Creates a name for the file being uploaded.
	 *
	 * @param Entity $object The object the upload is attached to.
	 * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
	 *
	 * @return string The file name.
	 */
	public function name($object, PropertyMapping $mapping) {
		$extension = $this->fixExtension($mapping->getFile($object));
		return $this->getBookIdFromEntity($object) .'-'. uniqid() .'.'. $extension;
	}

	private function fixExtension(\Symfony\Component\HttpFoundation\File\UploadedFile $file) {
		$extension = strtolower($this->getExtension($file));
		$extension = strtr($extension, [
			'tiff' => 'tif',
			'jpeg' => 'jpg',
		]);
		return $extension;
	}

	/**
	 * Creates a directory name for the file being uploaded.
	 *
	 * @param Entity $object The object the upload is attached to.
	 * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
	 *
	 * @return string The directory name.
	 */
	public function directoryName($object, PropertyMapping $mapping) {
		return Thumbnail::createSubPath($this->getBookIdFromEntity($object));
	}

	/**
	 * @param Entity $entity
	 * @return int
	 */
	private function getBookIdFromEntity($entity) {
		if ($entity instanceof Book) {
			return $entity->getId();
		}
		if ($entity instanceof BookFile) {
			return $entity->getBook()->getId();
		}
		return null;
	}
}
