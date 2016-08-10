<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare (strict_types=1);

namespace DisqusHelper\Widget;

use DisqusHelper\Exception\InvalidWidgetConfigurationException;
use DisqusHelper\Exception\InvalidWidgetException;
use DisqusHelper\Exception\WidgetNotFoundException;

class WidgetManager implements WidgetLocatorInterface
{
    /**
     * @var array
     */
    protected $widgets;

    private function __construct()
    {
    }

    public static function create(array $widgets) : WidgetManager
    {
        $widgetManager = new self();

        foreach ($widgets as $widgetId => $widget) {
            $widgetManager->registerWidget($widgetId, $widget);
        }

        return $widgetManager;
    }

    public static function createWithDefaultWidgets() : WidgetManager
    {
        return self::create([
            'thread' => ThreadWidget::class,
            'commentscount' => CommentsCountWidget::class,
        ]);
    }

    public function registerWidget(string $widgetId, $widget)
    {
        if (!($widget instanceof WidgetInterface || is_string($widget) || is_callable($widget))) {
            throw InvalidWidgetConfigurationException::forConfiguration($widgetId, $widget);
        }

        $widgetId = strtolower($widgetId);

        $this->widgets[$widgetId] = $widget;
    }

    public function has(string $widgetId) : bool
    {
        $widgetId = strtolower($widgetId);

        return isset($this->widgets[$widgetId]);
    }

    public function get(string $widgetId) : WidgetInterface
    {
        $widgetId = strtolower($widgetId);

        if (!isset($this->widgets[$widgetId])) {
            throw WidgetNotFoundException::forWidgetId($widgetId);
        }

        $widget = $this->createWidget($widgetId);

        if (!$widget instanceof WidgetInterface) {
            throw InvalidWidgetException::forWidget($widget);
        }

        return $widget;
    }

    protected function createWidget($widgetId)
    {
        $widget = $this->widgets[$widgetId];

        if (is_string($widget)) {
            return new $widget();
        }

        if (is_callable($widget)) {
            return $widget();
        }

        return $widget;
    }
}
