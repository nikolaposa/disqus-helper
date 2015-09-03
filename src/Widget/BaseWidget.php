<?php
/**
 * This file is part of the ZfDisqus package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper\Widget;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class BaseWidget implements WidgetInterface
{
    /**
     * @param array $attribs
     * @return string
     */
    protected function htmlAttribs(array $attribs)
    {
        $html = '';

        foreach ($attribs as $key => $val) {
            $key = htmlspecialchars($key, ENT_QUOTES);

            if (is_array($val)) {
                $val = implode(' ', $val);
            }

            if (strpos($val, '"') !== false) {
                $html .= " $key='$val'";
            } else {
                $html .= " $key=\"$val\"";
            }
        }

        return $html;
    }
}
