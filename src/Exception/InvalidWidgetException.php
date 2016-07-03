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

use DisqusHelper\Widget\WidgetInterface;

class InvalidWidgetException extends RuntimeException
{
    public static function forWidget($widget) : self
    {
        return new self(sprintf(
            "Widget must implement %s interface",
            WidgetInterface::class
        ));
    }
}