<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace DisqusHelper\Exception;

use RuntimeException;
use DisqusHelper\Widget\WidgetInterface;

class InvalidWidgetException extends RuntimeException implements ExceptionInterface
{
    public static function forWidget($widget) : self
    {
        return new self(sprintf(
            "Widget must implement %s interface",
            WidgetInterface::class
        ));
    }
}
