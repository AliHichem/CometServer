<?php

require_once ROOT_COMET . '/lib/plugin/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);
$loader->registerNamespaces(array(
    'Comet' => ROOT_COMET.'/lib',
));
$loader->register();
$GLOBALS['COMET_CLASS_LOADER'] = $loader;