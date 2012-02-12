<?php

/**
 * CometWebInterface
 *
 * @package     CometServer
 * @version     1.0.0
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometWebInterface
{

    private $runTimeLimit;
    private $cometSrv;
    private $defaultOptions = array(
        'runTimeLimit' => 0,
        'cometSrv' => COMET_LOCATION
    );
    private $key;
    private $timeKey;

    /**
     * Class constructor
     *
     * @param <type> $options
     * @return void
     */
    public function __construct(array $options = array(), $key = NULL)
    {
        $this->initOptions($options);
        $this->initMaxExecutionTime();
        $this->key = $key;
    }

    /**
     * Static class creator
     * 
     * @return self 
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Clean key
     *  Supported key type are:
     *      - numeric
     *      - string
     *      - numeric array 
     * 
     * @param type $key
     * 
     * @return type 
     */
    public function cleanKey($key)
    {
        try
        {
            $type = gettype($key);
            if (is_numeric($key))
            {
                return $key;
            }
            elseif ($type == "string")
            {
                return urlencode($key);
            }
            elseif ($type == 'array')
            {
                if (CometTools::isAssoc($key))
                {
                    throw new Exception('Key cannot be associative array');
                }
                else
                {
                    foreach ($key as $index => $item)
                    {
                        $key[$index] = urlencode($item);
                    }
                    return urlencode(serialize($key));
                }
            }
            else
            {
                throw new Exception('Key type invalid: valide types are (numeric, string, numeric array)');
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get time key
     * 
     * @param type $key
     * 
     * @return type 
     */
    private function getTimeKey()
    {
        return $this->cleanKey($this->key) . '-time';
    }

    /**
     * Initialize option:
     *  merge with default options
     * 
     * @param type $options 
     * 
     * @return void
     */
    private function initOptions($options)
    {
        $options = CometTools::arrayDeepMerge($this->defaultOptions, $options);
        $privates = array_keys($options);
        foreach ($privates as $key)
        {
            $this->$key = $options[$key];
        }
    }

    /**
     * Initialize php maximun execution time
     * 
     * @return void 
     */
    private function initMaxExecutionTime()
    {
        ini_set('max_execution_time', $this->runTimeLimit);
        $this->runTimeLimit = ini_get('max_execution_time');
    }

    /**
     * Read key value
     * 
     * @param type $key
     * 
     * @return type 
     */
    public function read()
    {
        $key = $this->cleanKey($this->key);
        $startTime = MICROTIME(TRUE);
        $read = $this->readFromComet($key);
        $exit = FALSE;
        $timeKey = $this->readFromComet($this->getTimeKey());
        while (!$exit)
        {
            usleep(100000);
            $get = $this->readFromComet($key);
            $time = $this->readFromComet($this->getTimeKey());
            if (($get != $read) || ($timeKey != $time))
            {
                $exit = TRUE;
            }
            if (($this->runTimeLimit <= (round((MICROTIME(TRUE) - $startTime), 0) - 5 )) && $this->runTimeLimit != 0)
            {
                $get = "";
                $exit = TRUE;
            }
        }
        return urldecode($get);
    }

    /**
     * Read both key value and time key value and return them in xml format
     * 
     * @param type $key
     * 
     * @return xml
     */
    public function readAsXml()
    {
        header ("Content-Type: text/xml");
        $cometData = $this->read($this->key);
        $cometTime = trim(str_replace("value:", "", $this->readFromComet($this->getTimeKey())));
        $doc = new DOMDocument('1.0','utf-8');
        $doc->formatOutput = true;

        $root = $doc->createElement('comet');
        $root = $doc->appendChild($root);

        $data = $doc->createElement('data');
        $data = $root->appendChild($data);
        $text = $doc->createCDATASection($cometData);
        $data->appendChild($text);

        $time = $doc->createElement('time');
        $time = $root->appendChild($time);
        $text = $doc->createCDATASection($cometTime);
        $time->appendChild($text);
        return $doc->saveXML();
    }

    /**
     * Read once from comet:
     *  similar to command line : [~$./Comet read key]
     * 
     * @param type $key
     * 
     * @return type 
     */
    public function readOnce($key)
    {
        $key = $this->cleanKey($key);
        return urldecode($this->readFromComet($key));
    }

    /**
     * Read from comt server
     * 
     * @param type $key
     * 
     * @return type 
     */
    private function readFromComet($key)
    {
        $cmd = "{$this->cometSrv} read {$key} 2>&1";
        ob_start();
        passthru($cmd);
        $result = trim(ob_get_contents());
        ob_end_clean();
        return $result;
    }

    /**
     * refresh current time key
     * 
     * @return type 
     */
    public function refresh()
    {
        return $this->writeToComet($this->getTimeKey(), time());
    }

    /**
     * Write value into key, also write time key
     * 
     * @param type $key
     * @param type $value 
     * 
     * @return type
     */
    public function write($value)
    {
        $key = $this->cleanKey($this->key);
        $responce = $this->writeToComet($key, urlencode($value));
        $this->writeToComet($this->getTimeKey(), time());
        return $responce;
    }

    /**
     * Write value as key into comet server
     * 
     * @param type $key
     * @param type $value
     * 
     * @return type 
     */
    private function writeToComet($key, $value)
    {
        $cmd = "{$this->cometSrv} write {$key} \"{$value}\" 2>&1";
        ob_start();
        passthru($cmd);
        $result = trim(ob_get_contents());
        ob_end_clean();
        return $result;
    }

    /**
     * Key getter
     * 
     * @return type 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Key setter
     * 
     * @param type $key 
     * 
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

}
