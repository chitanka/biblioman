<?php
require __DIR__.'/../../vendor/autoload.php';
$server = new Chitanka\ThumbnailServer\Server(__DIR__.'/../../data', __DIR__.'/../cache');
$server->serve();
