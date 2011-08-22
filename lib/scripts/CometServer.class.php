<?php

/**
 * CometServer
 *
 * @package     CometServer
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometServer
{

    private static $instance = NULL;
    private $serverName = "CometServer";
    private $serverVersion = "0.0.1";
    private $socketAddress = '/tmp/mysock';
    private $socketPort = 0;
    private $socketServer = NULL;
    private $socketClient = NULL;
    private $pidLocation = NULL;
    private $isDieing = FALSE;

    /**
     * class constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->initDaemon();
    }

    /**
     * class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        
    }

    /**
     * create class instance from static call
     *
     * @return object
     */
    public static function create()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * clone
     *
     * @return error
     */
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    /**
     * sleep
     *
     * @return error
     */
    public function __sleep()
    {
        trigger_error('Serializing is not allowed.', E_USER_ERROR);
    }

    /**
     * wakeup
     *
     * @return wakeup
     */
    public function __wakeup()
    {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }

    /**
     * Deamon initialzer
     *
     * @return void
     */
    private function initDaemon()
    {
        System_Daemon::setOption("appName", "comet");
        $appName = System_Daemon::getOption("appName");
        System_Daemon::setOption("appDir", APP_LOCATION);
        System_Daemon::setOption("authorName", "Ali Hichem");
        System_Daemon::setOption("authorEmail", "ali.hichem@mail.com");
        System_Daemon::setOption("appPidLocation", APP_LOCATION . "/{$appName}/{$appName}.pid");
        System_Daemon::setOption("logLocation", APP_LOCATION . "/{$appName}/{$appName}.log");
        System_Daemon::setOption("usePEAR", FALSE);
        System_Daemon::setOption("logVerbosity", System_Daemon::LOG_WARNING);
        $this->appPidLocation = System_Daemon::opt('appPidLocation');
        $this->socketAddress = APP_LOCATION . "/{$appName}/{$appName}.sock";
    }

    /**
     * Socket initializer
     *
     * @return void
     */
    private function initSocket()
    {
        $this->socketServer = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if ($this->socketServer == FALSE)
        {
            echo CometCliColor::format('KO', 'ERROR') . "]\n";
            $this->printMessage(socket_strerror(socket_last_error()) . " l[" . __LINE__ . "]");
            System_Daemon::stop();
            exit;
        }
    }

    /**
     * bind socket server
     *
     * @return void
     */
    private function bindSockServer()
    {
        if (!socket_bind($this->socketServer, $this->socketAddress, $this->socketPort))
        {
            $this->printMessage(socket_strerror(socket_last_error()) . " l[" . __LINE__ . "]");
            System_Daemon::stop();
            exit;
        }
        else
        {
            chmod($this->socketAddress, 0777);
        }
    }

    /**
     * close socket
     *
     * @return void
     */
    private function closeSocket()
    {
        socket_close($this->socketServer);
        if (file_exists($this->socketAddress))
        {
            @unlink($this->socketAddress);
        }
    }

    /**
     * check if deamon is running
     *  if not : remove pidand socket files if exists
     * 
     * @return boolean
     */
    public function isRunning()
    {
        $isRunning = System_Daemon::isRunning();
        if (!$isRunning && file_exists($this->socketAddress))
        {
            @unlink($this->socketAddress);
        }
        return $isRunning;
    }

    /**
     * get server pid
     *
     * @return <type>
     */
    public function getServerPid()
    {
        return System_Daemon::fileread($this->appPidLocation);
    }

    /**
     * dump all stored keys/values
     * 
     * @return type 
     */
    public function dump()
    {
        return $this->send("dump");
    }

    /**
     * send Write command to deamon
     * 
     * @param type $key
     * @param type $value 
     * 
     * @return void
     */
    public function write($key, $value)
    {
        $clean = urlencode($value);
        $this->send("write {$key} {$clean}");
    }

    /**
     * send Read command to deamon
     * 
     * @param type $key
     * 
     * @return value
     */
    public function read($key)
    {
        $clean = urldecode($this->send("read {$key}"));
        return $clean;
    }

    /**
     * start server as deamon:
     *  create fork that will communicate with new parent via socket
     *
     * @return void
     */
    public function start()
    {
        System_Daemon::start();
        try
        {
            $this->initSocket();
            $this->bindSockServer();
            socket_listen($this->socketServer);
            echo CometCliColor::format('OK', 'INFO') . "]\n";
            $data = array();
            while (!$this->isDieing)
            {
                $client = socket_accept($this->socketServer);
                $input = $this->socketRead($client);
                $inputs = explode(" ", $input);
                $output = NULL;
                switch ($inputs[0])
                {
                    case "stop":
                        $this->isDieing = TRUE;
                        $output = "ok";
                        break;
                    case "write":
                        $data[$inputs[1]] = $inputs[2];
                        $output = "ok";
                        break;
                    case "read":
                        $output = $data[$inputs[1]];
                        break;
                    case "dump":
                        $i = 0;
                        foreach ($data as $index => $item)
                        {
                            $output .= "\n " . CometCliColor::format("* index [{$i}] - {$index}", 'INFO') . ": [{$item}]";
                            $i++;
                        }
                        unset($i,$index,$item);
                        break;
                    default:
                        $output = "No valid command recived";
                        break;
                }
                $this->socketWrite($client, $output);
                socket_close($client);
                unset($input, $output, $inputs, $client );
                flush();
            }
            $this->closeSocket();
        }
        catch (Excception $e)
        {
            echo socket_strerror(socket_last_error());
        }
        System_Daemon::stop();
    }

    /**
     * Send message to deamon and return response
     *
     * @param <type> $msg
     * @return <type>
     */
    private function send($msg)
    {
        try
        {
            $this->initSocket();
            socket_connect($this->socketServer, $this->socketAddress, $this->socketPort);
            $this->socketWrite($this->socketServer, $msg);
            $input = $this->socketRead($this->socketServer);
            return $input;
        }
        catch (Excception $e)
        {
            echo socket_strerror(socket_last_error());
        }
    }

    /**
     * Stop deamon
     *
     * @return string
     */
    public function stop()
    {
        return $this->send("stop");
    }

    /**
     * socket write
     *  can read illimited message
     * 
     * @param type $socket
     * @param type $end
     * 
     * @return type 
     */
    private function socketRead($socket, $end=array("\r", "\0"))
    {
        if (is_array($end))
        {
            foreach ($end as $k => $v)
            {
                $end[$k] = $v{0};
            }
            $string = '';
            while (TRUE)
            {
                $char = socket_read($socket, 1);
                $string.=$char;
                foreach ($end as $k => $v)
                {
                    if ($char == $v)
                    {
                        unset($end, $k, $v, $char);
                        return $string;
                    }
                }
            }
        }
        else
        {
            $endr = str_split($end);
            $try = count($endr);
            $string = '';
            while (TRUE)
            {
                $ver = 0;
                foreach ($endr as $k => $v)
                {
                    $char = socket_read($socket, 1);
                    $string.=$char;
                    if ($char == $v)
                    {
                        $ver++;
                    }
                    else
                    {
                        break;
                    }
                    if ($ver == $try)
                    {
                        unset($end, $endr, $try, $ver, $k, $v, $char);
                        return $string;
                    }
                }
            }
        }
    }

    /**
     * socket write
     * 
     * @param type $socket
     * @param type $msg
     * 
     * @return type 
     */
    private function socketWrite($socket, $msg)
    {
        return socket_write($socket, $msg . " \0");
    }

    /**
     * Print message to screen
     *
     * @param <type> $msg
     *
     * @return  string
     */
    public function printMessage($msg)
    {
        echo "\n{$msg}";
    }

    /**
     * Server name getter
     *
     * @return  string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * Server name setter
     *
     * @param <type> $serverName
     *
     * @return void
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * Socket adress getter
     *
     * @return string
     */
    public function getSocketAddress()
    {
        return $this->socketAddress;
    }

    /**
     * Socket adress setter
     *
     * @param <type> $socketAddress
     * 
     * @return void
     */
    public function setSocketAddress($socketAddress)
    {
        $this->socketAddress = $socketAddress;
    }

    /**
     * Socket port getter
     *
     * @return int
     */
    public function getSocketPort()
    {
        return $this->socketPort;
    }

    /**
     * Socket port setter
     *
     * @param <type> $socketPort
     *
     * @return void
     */
    public function setSocketPort($socketPort)
    {
        $this->socketPort = $socketPort;
    }

    /**
     * Socket server getter
     *
     * @return string
     */
    public function getSocketServer()
    {
        return $this->socketServer;
    }

    /**
     * Socket server setter
     *
     * @param <type> $socketServer
     *
     * @return void
     */
    public function setSocketServer($socketServer)
    {
        $this->socketServer = $socketServer;
    }

    /**
     * PidLocation getter
     *
     * @return string
     */
    public function getPidLocation()
    {
        return $this->pidLocation;
    }

    /**
     * PidLocation setter
     *
     * @param <type> $pidLocation
     *
     * @return void
     */
    public function setPidLocation($pidLocation)
    {
        $this->pidLocation = $pidLocation;
    }

}
