<?php
/**
 * Bootstrap tests
 */
if(file_exists(__DIR__.'/../vendor/autoload.php')) {
	$loader = require_once __DIR__.'/../vendor/autoload.php';
} else if (file_exists(__DIR__.'/../../../../vendor/autoload.php')) {
	$loader = require_once __DIR__.'/../../../../vendor/autoload.php';
}
if(!isset($loader)) {
	die("No autoload class found!");
}
use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
