<?php

define("APP_LOCATION", dirname(__FILE__)."/..");
define("COMET_LOCATION", dirname(__FILE__)."/../Comet");
require_once APP_LOCATION."/lib/System/Daemon.php";

spl_autoload_register(array('CometAutoloader', 'autoload'));
/**
 * CometAutoloader
 *
 * @package     CometServer
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometAutoloader
{

    /**
     * Autoload static method for loading classes and interfaces.
     * Code from the PHP_CodeSniffer package by Greg Sherwood and
     * Marc McIntyre
     *
     * @param string $className The name of the class or interface.
     *
     * @return void
     */
    static public function autoload($className)
    {
        $path = APP_LOCATION . '/lib/scripts/' . $className . '.class.php';
        if (is_file($path) === true)
        {
            require_once $path;
        }
    }

}
