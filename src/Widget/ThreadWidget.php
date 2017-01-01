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

use DisqusHelper\Code;

/**
 * Comments thread widget.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class ThreadWidget implements WidgetInterface
{
    const SCRIPT_NAME = 'embed.js';

    public function render(array $options = []) : string
    {
        return '<div id="disqus_thread"></div>';
    }

    public function visit(Code $code) : Code
    {
        $code->addScriptFile(self::SCRIPT_NAME, [
            'lazy_load' => true,
        ]);

        return $code;
    }
}
