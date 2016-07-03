<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper\Exception;

use InvalidArgumentException;
use DisqusHelper\Widget\WidgetInterface;

class InvalidWidgetConfigurationException extends InvalidArgumentException implements ExceptionInterface
{
    public static function forConfiguration(string $widgetId, $widget) : self
    {
        return new self(sprintf(
            "Invalid configuration for '%' widget; widget should be either string, callable or %s instance, %s given",
            $widgetId,
            WidgetInterface::class,
            gettype($widget)
        ));
    }
}