<?php

namespace Comet\Boot;

/**
 * Bootstrap
 *
 * @package     CometServer
 * @version     2.0.0
 * @author      Ali hichem <ali.hichem@mail.com>
 */
abstract class Bootstrap
{

    /** @var Configuration */
    public static $instance;

    /**
     * Class constructor
     */
    public function __construct()
    {
        self::$instance = $this;
        ini_set('display_errors', 1);
    }

    /**
     * run all method in the bootstrap prefixed with 'init'
     * 
     * @return void
     */
    public function boot()
    {
        try
        {
            foreach (get_class_methods($this) as $method)
            {
                if (preg_match("~^init~", $method))
                {
                    $this->$method();
                }
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

}