<?php

$loader = require_once __DIR__.'/../../../../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$kernelBooter = new \Mekit\Bundle\TestBundle\phpunitBootstrap\KernelBooter();
$kernelBooter->boot();
