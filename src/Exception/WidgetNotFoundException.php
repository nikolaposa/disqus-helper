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

use RuntimeException;

class WidgetNotFoundException extends RuntimeException implements ExceptionInterface
{
    public static function forWidgetId(string $widgetId) : self
    {
        return new self(sprintf("Unable to find '%s' widget", $widgetId));
    }
}