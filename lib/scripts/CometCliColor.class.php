<?php

/**
 * CometCliColor
 *
 * @package     CometServer
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometCliColor
{

    protected
    $styles = array(
        'ERROR' => array('bg' => 'red', 'fg' => 'white', 'bold' => true),
        'INFO' => array('fg' => 'green', 'bold' => true),
        'COMMENT' => array('fg' => 'yellow'),
        'QUESTION' => array('bg' => 'cyan', 'fg' => 'black', 'bold' => false),
            ),
    $options = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8),
    $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37),
    $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);

    /**
     * Sets a new style.
     *
     * @param string $name    The style name
     * @param array  $options An array of options
     */
    public function setStyle($name, $options = array())
    {
        $this->styles[$name] = $options;
    }

    /**
     * Formats a text according to the given style or parameters.
     *
     * @param  string   $text       The test to style
     * @param  mixed    $parameters An array of options or a style name
     *
     * @return string The styled text
     */
    public function _format($text = '', $parameters = array())
    {
        if (!is_array($parameters) && 'NONE' == $parameters)
        {
            return $text;
        }

        if (!is_array($parameters) && isset($this->styles[$parameters]))
        {
            $parameters = $this->styles[$parameters];
        }

        $codes = array();
        if (isset($parameters['fg']))
        {
            $codes[] = $this->foreground[$parameters['fg']];
        }
        if (isset($parameters['bg']))
        {
            $codes[] = $this->background[$parameters['bg']];
        }
        foreach ($this->options as $option => $value)
        {
            if (isset($parameters[$option]) && $parameters[$option])
            {
                $codes[] = $value;
            }
        }

        return "\033[" . implode(';', $codes) . 'm' . $text . "\033[0m";
    }

    public static function format($text = '', $parameters = array())
    {
        $instance = new self();
        return $instance->_format($text, $parameters);
    }

}
