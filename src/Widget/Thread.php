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
 * Renders Disqus comments thread.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Thread implements WidgetInterface
{
    public function getScriptName()
    {
        return 'embed.js';
    }

    public function render(array $options = array())
    {
        return '<div id="disqus_thread"></div>';
    }
}
