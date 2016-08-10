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

use DisqusHelper\Exception\WidgetNotFoundException;

interface WidgetLocatorInterface
{
    /**
     * @param string $widgetId
     *
     * @throws WidgetNotFoundException
     *
     * @return WidgetInterface
     */
    public function get(string $widgetId) : WidgetInterface;
}
