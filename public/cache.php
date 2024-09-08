<?php

function isCacheable(): bool {
	return filter_input(INPUT_SERVER, 'CACHE_ENABLE') && $_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_COOKIE['mlt']);
}

class Cache {
	private $file;
	private $request;
	private $debug = false;
	private $logFile;

	public function __construct($requestUri, $cacheDir, $logDir = '', $compressCache = true) {
		$hash = md5($requestUri);
		$this->file = new CacheFile("$cacheDir/$hash[0]/$hash[1]/$hash[2]/$hash", $compressCache);
		$this->request = $requestUri;
		$this->logFile = "$logDir/cache.log";
	}

	public function get() {
		if ( ! $this->file->exists()) {
			return null;
		}
		$ttl = $this->file->getTtl();
		if ($ttl <= 0) {
			$this->purge();
			return null;
		}
		$this->log("=== HIT");
		return [
			'data' => $this->file->read(),
			'ttl' => $ttl,
		];
	}
	/**
	 * Set cache content with a given time to live.
	 * @param string $content
	 * @param int $ttl Time to live (in seconds)
	 */
	public function set($content, $ttl) {
		if (!$ttl) {
			$this->log("/// SKIP");
			return;
		}
		$this->file->write($content, $ttl);
		$this->log("+++ MISS ($ttl)");
	}
	private function purge() {
		$this->file->delete();
		$this->log('--- PURGE');
	}
	private function log($msg) {
		if ($this->debug) {
			file_put_contents($this->logFile, "$msg - $this->request\n", FILE_APPEND);
		}
	}
}
class CacheFile {
	private $name;
	private $compressed = true;

	public function __construct($name, $compresed = true) {
		$this->name = $name;
		$this->compressed = $compresed;
	}
	public function exists() {
		return file_exists($this->name);
	}

	/**
	 * @param string $content
	 * @param integer $ttl
	 */
	public function write($content, $ttl) {
		if ( ! file_exists($dir = dirname($this->name))) {
			mkdir($dir, 0777, true);
		}
		$content = ltrim($content);
		file_put_contents($this->name, $this->compressed ? gzencode($content, 9) : $content);
		$this->setTtl($ttl);
	}
	public function read() {
		$content = file_get_contents($this->name);
		if (empty($content)) {
			return $content;
		}
		if (substr($content, 0, 3) !== "\x1f\x8b\x08") {
			// probably uncompressed, return as-is
			return $content;
		}
		return gzdecode($content);
	}
	public function delete() {
		unlink($this->name);
	}
	/**
	 * The time to live is set implicitly through the last modification time, e.g.
	 * if a file has TTL of 1 hour, its modification time is set to 1 hour in the future
	 * @param integer $ttl
	 */
	private function setTtl($ttl) {
		touch($this->name, time() + $ttl);
	}
	public function getTtl() {
		return filemtime($this->name) - time()
			+ rand(0, 30) /* guard for race conditions */;
	}
}
