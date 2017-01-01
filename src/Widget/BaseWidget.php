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

namespace DisqusHelper\Widget;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class BaseWidget implements WidgetInterface
{
    protected function htmlAttribsToString(array $attribs) : string
    {
        $html = '';

        foreach ($attribs as $key => $val) {
            $key = htmlspecialchars($key, ENT_QUOTES);
            $val = htmlspecialchars($val, ENT_QUOTES);

            $html .= "$key=\"$val\" ";
        }

        $html = rtrim($html);

        return $html;
    }
}
