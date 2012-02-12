<?php

/**
 * CometArgumentHandler
 *
 * @package     CometServer
 * @version     1.0.0
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometArgumentHandler
{

    private $err = array();
    private $args = NULL;
    private $help = NULL;
    private $parentCall = FALSE;
    private $command = array();
    private $argumentsL1 = array("start", "stop", "status", "write", "read", "help","dump");
    private $serverName = NULL;
    private $defaultOptions = array(
        "args" => array(),
        "serverName" => "CometServer V 0.0.1"
    );

    /**
     * Class constructor
     *
     * @param <type> $options
     * @return void
     */
    public function __construct($options)
    {
        $this->initOptions($options);
        $this->initConstants();
        $this->prepare();
    }

    /**
     * Initialize option:
     *  merge with default options
     * 
     * @param type $options 
     * 
     * @return void
     */
    public function initOptions($options)
    {
        $options = CometTools::arrayDeepMerge($this->defaultOptions, $options);
        $privates = array_keys($options);
        foreach ($privates as $key)
        {
            $this->$key = $options[$key];
        }
    }

    /**
     * Initialize constant
     * 
     * @return void
     */
    public function initConstants()
    {
        foreach ($this->argumentsL1 as $key)
        {
            define(strtoupper($key), $key);
        }
    }

    /**
     * prepare for handling cli arguments
     *
     * @return void
     */
    public function prepare()
    {
        $this->setHelp();
        if ($this->validateArgv())
        {
            $this->parseArgv();
        }
        else
        {
            $this->throwErrors();
        }
    }

    /**
     * parse cli arguments
     *
     * @return void
     */
    public function parseArgv()
    {
        $this->command['command'] = isset($this->args[1]) ? $this->args[1] : NULL;
        $this->command['key'] = isset($this->args[2]) ? $this->args[2] : NULL;
        $this->command['value'] = isset($this->args[3]) ? $this->args[3] : NULL;
    }

    /**
     * validates cli arguments
     *
     * @return boolean
     */
    public function validateArgv()
    {
        if (count($this->args) == 1)
        {
            $this->err[] = "\r  No arguments received: type help to list available commands";
            return FALSE;
        }
        if (count($this->args) > 4)
        {
            $this->err[] = "\r  Too many arguments received: type help to list available commands";
            return FALSE;
        }
        if (!isset($this->args[1]) || !in_array($this->args[1], $this->argumentsL1))
        {
            $this->err[] = "\r  Invalid command: type help to list available commands";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * print if exists error list to screen
     *
     * @return void
     */
    public function throwErrors()
    {
        $messages = "";
        foreach ($this->err as $msg)
        {
            $messages .= "\n {$msg}";
        }
        $this->throwMessage($messages);
        exit(128);
    }

    public function printMessage($msg, $endWithNewLine = FALSE)
    {
        echo "\r{$msg}";
        if ($endWithNewLine)
        {
            echo "\n";
        }
    }

    /**
     * print message to screen and exit script
     *
     * @param <type> $msg
     * @return void
     */
    public function throwMessage($msg, $exit = TRUE)
    {
        echo CometCliColor::format($this->serverName, 'COMMENT');
        echo "\n {$msg}";
        if ($exit)
        {
            echo "\n";
            exit(128);
        }
    }

    /**
     * help getter
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * help setter
     *
     * @return void
     */
    public function setHelp()
    {
        $this->help = "\n  Arguments list:";
        $this->help .= "\n         start               # start server";
        $this->help .= "\n         stop                # stop server";
        $this->help .= "\n         status              # describe server status";
        $this->help .= "\n         write key \"value\"   # write value into key";
        $this->help .= "\n         read key            # read key";
        $this->help .= "\n         dump                # dump all existing keys";
    }

    /**
     * check if first cli argument will be interpreted as a call for
     * main process
     *
     * @return boolean
     */
    public function isParentCall()
    {
        return $this->parentCall;
    }

    /**
     * Command getter
     * 
     * @return type 
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Command setter
     * 
     * @param type $command 
     * 
     * @return void
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * ServerName getter
     * 
     * @return type 
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * ServerName setter
     * 
     * @param type $serverName 
     * 
     * @return void
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * ArgumentsL1 getter
     *
     * @return type 
     */
    public function getArgumentsL1()
    {
        return $this->argumentsL1;
    }

    /**
     * ArgumentsL1 setter
     * 
     * @param type $argumentsL1 
     * 
     * @return type
     */
    public function setArgumentsL1($argumentsL1)
    {
        $this->argumentsL1 = $argumentsL1;
    }

}