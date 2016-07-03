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

use DisqusHelper\Widget\WidgetLocatorInterface;
use DisqusHelper\Widget\WidgetManager;

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
     * @var WidgetLocatorInterface
     */
    private $widgetLocator;

    /**
     * @var array
     */
    private $usedWidgets = [];

    /**
     * @var bool
     */
    private $initialized = false;

    public function __construct()
    {
    }

    /**
     * @param string $shortName Unique identifier of some Disqus website.
     * @param array $config OPTIONAL Any additional Disqus configuration (https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables).
     * @param WidgetLocatorInterface $widgetLocator OPTIONAL
     *
     * @return Disqus
     */
    public static function create(
        string $shortName,
        array $config = [],
        WidgetLocatorInterface $widgetLocator = null
    ) {
        $disqusHelper = new self();

        $disqusHelper->config = array_merge(['shortname' => $shortName], $config);

        if (is_null($widgetLocator)) {
            $widgetLocator = WidgetManager::createWithDefaultWidgets();
        }

        $disqusHelper->widgetLocator = $widgetLocator;

        return $disqusHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Overload method access; proxies calls to appropriate Disqus widget
     * and returns HTML output.
     *
     * @param  string $method
     * @param  array  $args
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return string
     */
    public function __call($method, $args)
    {
        $widgetId = $method;

        $widget = $this->widgetLocator->get($widgetId);

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

        if (!isset($this->usedWidgets[$widgetId])) {
            $this->usedWidgets[$widgetId] = $widget;
        }

        return $widget->render($options ?: []);
    }

    /**
     * Loads Disqus configuration and necessary assets for used widgets.
     *
     * This method should be called after using and rendering widgets, usually before closing </body> tag.
     *
     * @param array $config OPTIONAL Disqus configuration (https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables)
     *
     * @throws Exception\RuntimeException
     *
     * @return string
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
