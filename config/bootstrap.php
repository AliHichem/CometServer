<?php

define('ROOT_COMET', realpath(__DIR__."/../"));

require_once ROOT_COMET . '/lib/Comet/Loader/ClassLoader.php';

use Comet\Boot\Bootstrap as BaseBootstrap;

class Bootstrap extends BaseBootstrap
{

    public function initLoader()
    {
        $class_loader = $GLOBALS['COMET_CLASS_LOADER'];
        /* @var $class_loader Symfony\Component\ClassLoader\UniversalClassLoader */
        $class_loader->registerPrefixes(array(
          'System_' => ROOT_COMET.'/lib/plugin',
        ));
        $class_loader->register();
    }
    
    public function initLogger()
    {
    }

}

$bootstrap = new Bootstrap();
$bootstrap->boot();