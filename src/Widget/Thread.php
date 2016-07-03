<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper\Widget;

/**
 * Comments thread widget.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Thread implements WidgetInterface
{
    const SCRIPT_NAME = 'embed.js';

    public function getScriptName()
    {
        return self::SCRIPT_NAME;
    }

    public function render(array $options = [])
    {
        return '<div id="disqus_thread"></div>';
    }
}
