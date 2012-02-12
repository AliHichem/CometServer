<?php

namespace Comet\Util ;

/**
 * Tools
 *
 * @package     CometServer
 * @version     2.0.0
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class Tools
{

    // Code from symfony sfToolkit class. See LICENSE
    // code from php at moechofe dot com (array_merge comment on php.net)
    /*
     * arrayDeepMerge
     *
     * array arrayDeepMerge ( array array1 [, array array2 [, array ...]] )
     *
     * Like array_merge
     *
     *  arrayDeepMerge() merges the elements of one or more arrays together so
     * that the values of one are appended to the end of the previous one. It
     * returns the resulting array.
     *  If the input arrays have the same string keys, then the later value for
     * that key will overwrite the previous one. If, however, the arrays contain
     * numeric keys, the later value will not overwrite the original value, but
     * will be appended.
     *  If only one array is given and the array is numerically indexed, the keys
     * get reindexed in a continuous way.
     *
     * Different from array_merge
     *  If string keys have arrays for values, these arrays will merge recursively.
     */
    public static function arrayDeepMerge()
    {
        switch (func_num_args())
        {
            case 0:
                return false;
            case 1:
                return func_get_arg(0);
            case 2:
                $args = func_get_args();
                $args[2] = array();

                if (is_array($args[0]) && is_array($args[1]))
                {
                    foreach (array_unique(array_merge(array_keys($args[0]), array_keys($args[1]))) as $key)
                    {
                        $isKey0 = array_key_exists($key, $args[0]);
                        $isKey1 = array_key_exists($key, $args[1]);

                        if ($isKey0 && $isKey1 && is_array($args[0][$key]) && is_array($args[1][$key]))
                        {
                            $args[2][$key] = self::arrayDeepMerge($args[0][$key], $args[1][$key]);
                        }
                        else if ($isKey0 && $isKey1)
                        {
                            $args[2][$key] = $args[1][$key];
                        }
                        else if (!$isKey1)
                        {
                            $args[2][$key] = $args[0][$key];
                        }
                        else if (!$isKey0)
                        {
                            $args[2][$key] = $args[1][$key];
                        }
                    }

                    return $args[2];
                }
                else
                {
                    return $args[1];
                }
            default:
                $args = func_get_args();
                $args[1] = self::arrayDeepMerge($args[0], $args[1]);
                array_shift($args);

                return call_user_func_array(array('Tools', 'arrayDeepMerge'), $args);
                break;
        }
    }

    /**
     * Checks if array is associative
     * 
     * @param type $arr
     * 
     * @return boolean
     */
    public static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}
