<?php
/**
 * This file is part of the DisqusHelper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper;

use DisqusHelper\Widget\WidgetInterface as Widget;
use DisqusHelper\Exception\BadMethodCallException;
use DisqusHelper\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Disqus
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $widgets = array(
        'thread' => 'DisqusHelper\\Widget\\Thread',
        'commentscount' => 'DisqusHelper\\Widget\\CommentsCount'
    );

    private $usedWidgets = array();

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @param string $shortName Unique identifier of some Disqus website.
     * @param array $config Any additional Disqus configuration
     * @link https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables
     */
    public function __construct($shortName, array $config = array())
    {
        $config = array_merge(array('shortname' => $shortName), $config);
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $name
     * @return Widget|null
     */
    private function getWidget($name)
    {
        $name = strtolower($name);

        if (isset($this->widgets[$name])) {
            $widget = $this->widgets[$name];

            if (!$widget instanceof Widget) {
                $this->widgets[$name] = $widget = new $widget();
            }

            return $widget;
        }

        return null;
    }

    /**
     * Overload method access; proxies calls to appropriate Disqus widget
     * and returns HTML output.
     *
     * @param  string $method
     * @param  array  $args
     * @throws BadMethodCallException
     * @return string
     */
    public function __call($method, $args)
    {
        $widgetName = $method;

        $widget = $this->getWidget($widgetName);

        if ($widget === null) {
            throw new BadMethodCallException("'$method' widget does not exist");
        }

        if (!isset($this->usedWidgets[$widgetName])) {
            $this->usedWidgets[$widgetName] = $widget;
        }

        return call_user_func_array(array($widget, 'render'), $args);
    }

    /**
     * Initializes used widgets by loading/rendering necessary assets.
     *
     * @return string
     * @throws RuntimeException
     */
    public function init()
    {
        if ($this->initialized) {
            throw new RuntimeException(get_class($this) . ' widget has already been initialized');
        }

        $html = '<script type="text/javascript">';
        $indent = '    ';

        foreach ($this->config as $key => $value) {
            $html .= PHP_EOL;

            if (is_string($value)) {
                $value = addslashes((string) $value);
                $value = "'$value'";
            }
            $html .= $indent . "var disqus_$key = $value;";
        }

        $html .= PHP_EOL . PHP_EOL;

        foreach ($this->usedWidgets as $widget) {
            $html .= $indent . '(function() {' . PHP_EOL;
            $html .= $indent . $indent . 'var s = document.createElement("script");' . PHP_EOL;
            $html .= $indent . $indent . 's.type = "text/javascript";' . PHP_EOL;
            $html .= $indent . $indent . 's.async = true;' . PHP_EOL;
            $html .= $indent . $indent . 's.src = "//" + disqus_shortname + ".disqus.com/' . $widget->getScriptName() . '"' . PHP_EOL;
            $html .= $indent . $indent . '(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(s)' . PHP_EOL;
            $html .= $indent . '})();' . PHP_EOL;

            $html .= PHP_EOL;
        }

        $html .= '</script>';

        $this->initialized = true;

        return $html;
    }
}
