<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper;

use DisqusHelper\Widget\WidgetInterface as Widget;

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
    private $widgets = [
        'thread' => 'DisqusHelper\\Widget\\Thread',
        'commentscount' => 'DisqusHelper\\Widget\\CommentsCount'
    ];

    /**
     * @var array
     */
    private $usedWidgets = [];

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @param string $shortName Unique identifier of some Disqus website.
     * @param array $config OPTIONAL Any additional Disqus configuration.
     * @link https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables
     */
    public function __construct($shortName, array $config = [])
    {
        $config = array_merge(['shortname' => $shortName], $config);
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
     * @throws Exception\BadMethodCallException
     * @throws Exception\InvalidArgumentException
     * @return string
     */
    public function __call($method, $args)
    {
        $widgetName = $method;

        $widget = $this->getWidget($widgetName);

        if ($widget === null) {
            throw new Exception\BadMethodCallException("'$method' widget does not exist");
        }

        if (($options = array_shift($args)) !== null) {
            if (!is_array($options)) {
                throw new Exception\InvalidArgumentException("Widget options argument should be array");
            }
        }

        if (($config = array_shift($args)) !== null) {
            if (!is_array($config)) {
                throw new Exception\InvalidArgumentException("Disqus configuration argument should be array");
            }

            $this->config = array_merge($this->config, $config);
        }

        if (!isset($this->usedWidgets[$widgetName])) {
            $this->usedWidgets[$widgetName] = $widget;
        }

        return $widget->render($options ?: []);
    }

    /**
     * Loads Disqus configuration and necessary assets for used widgets.
     *
     * This method should be called after using and rendering widgets, usually before closing </body> tag.
     *
     * @param array $config OPTIONAL Disqus configuration (https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables)
     * @return string
     * @throws Exception\RuntimeException
     */
    public function __invoke(array $config = [])
    {
        if ($this->initialized) {
            throw new Exception\RuntimeException(get_class($this) . ' widget has already been initialized');
        }

        $config = array_merge($this->config, $config);

        $html = '<script type="text/javascript">';
        $indent = '    ';

        foreach ($config as $key => $value) {
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
            $html .= $indent . $indent . 's.src = "//" + disqus_shortname + ".disqus.com/' . $widget->getScriptName() . '";' . PHP_EOL;
            $html .= $indent . $indent . '(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(s);' . PHP_EOL;
            $html .= $indent . '})();' . PHP_EOL;

            $html .= PHP_EOL;
        }

        $html .= '</script>';

        $this->initialized = true;

        return $html;
    }
}
