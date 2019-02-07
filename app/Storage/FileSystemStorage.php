<?php namespace App\Storage;

use Vich\UploaderBundle\Mapping\PropertyMapping;

class FileSystemStorage extends \Vich\UploaderBundle\Storage\FileSystemStorage {

	protected function doRemove(PropertyMapping $mapping, $dir, $name) {
		$file = $this->doResolvePath($mapping, $dir, $name);
		if (!file_exists($file)) {
			$file = preg_replace('/(jpg|png)$/', 'tif', $file);
		}
		if (!file_exists($file)) {
			return false;
		}
		return rename($file, $this->generateDeletedFileName($file));
	}

	protected function generateDeletedFileName($file) {
		return $file . '.deleted';
	}
}
