<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

require __DIR__.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespace('Dellysse\Csv', __DIR__.'/lib');
$loader->register();

unset($loader);
