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

use DisqusHelper\Code;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface WidgetInterface
{
    /**
     * @param array $options OPTIONAL
     *
     * @return string
     */
    public function render(array $options = []) : string;

    /**
     * @param Code $code
     *
     * @return Code
     */
    public function visit(Code $code) : Code;
}
