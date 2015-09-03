<?php
/**
 * This file is part of the DisqusHelper package.
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
interface WidgetInterface
{
    /**
     * @return string
     */
    public function getScriptName();

    /**
     * @param array $options OPTIONAL
     * @return string
     */
    public function render(array $options = array());
}
