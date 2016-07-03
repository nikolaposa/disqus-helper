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
     * @var string
     */
    private $shortName;

    /**
     * @var WidgetLocatorInterface
     */
    private $widgetLocator;

    /**
     * @var Code
     */
    private $code;

    public function __construct()
    {
    }

    /**
     * @param string $shortName Unique identifier of some Disqus website.
     * @param WidgetLocatorInterface $widgetLocator OPTIONAL
     *
     * @return Disqus
     */
    public static function create(
        string $shortName,
        WidgetLocatorInterface $widgetLocator = null
    ) : Disqus {
        $disqusHelper = new self();

        $disqusHelper->shortName = $shortName;

        if (is_null($widgetLocator)) {
            $widgetLocator = WidgetManager::createWithDefaultWidgets();
        }

        $disqusHelper->widgetLocator = $widgetLocator;

        $disqusHelper->code = Code::create($shortName);

        return $disqusHelper;
    }

    public function getShortName() : string
    {
        return $this->shortName;
    }

    public function configure(array $config)
    {
        $this->code->mergeConfig($config);
    }

    /**
     * Proxies calls to some Disqus widget and returns HTML output.
     *
     * @param string $widgetId
     * @param array $args
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return string
     */
    public function __call($widgetId, $args) : string
    {
        $widget = $this->widgetLocator->get($widgetId);

        if (($options = array_shift($args)) !== null) {
            if (!is_array($options)) {
                throw new Exception\InvalidArgumentException("Widget options argument should be array");
            }
        }

        $widget->visit($this->code);

        return $widget->render($options ?: []);
    }

    /**
     * Builds JS code which loads configuration and necessary assets.
     *
     * This method should be called after using and rendering widgets, usually before closing </body> tag.
     *
     * @return string
     */
    public function getCode() : string
    {
        return $this->code->toHtml();
    }

    public function __toString() : string
    {
        return $this->getCode();
    }
}
