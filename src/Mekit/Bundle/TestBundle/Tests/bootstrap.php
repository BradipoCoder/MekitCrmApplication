<?php
/**
 * Bootstrap tests
 */
if(file_exists(__DIR__.'/../../../../../vendor/autoload.php')) {
	$loader = require_once __DIR__ . '/../../../../../vendor/autoload.php';
}
if(!isset($loader)) {
	die("Cannot bootstrap unit tests! No autoload class found!");
}
use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
