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

use DisqusHelper\Code;
use DisqusHelper\Exception\RuntimeException;

/**
 * Comments count widget.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class CommentsCountWidget extends BaseWidget
{
    const SCRIPT_NAME = 'count.js';

    /**
     * @var array
     */
    private static $defaultOptions = [
        'url' => null,
        'label' => null,
        'as_link' => true,
        'identifier' => null,
    ];

    public function render(array $options = []) : string
    {
        $options = array_merge(self::$defaultOptions, $options);

        $label = htmlspecialchars((string) $options['label'], ENT_QUOTES, 'UTF-8');

        $attribs = [];

        if (isset($options['identifier'])) {
            $attribs['data-disqus-identifier'] = $options['identifier'];
        }

        if ($options['as_link']) {
            if (empty($options['url'])) {
                throw new RuntimeException("URL option is missing for the Comments count widget");
            }

            return $this->renderLink($options['url'] . '#disqus_thread', $label, $attribs);
        }

        if (!empty($options['url'])) {
            $attribs['data-disqus-url'] = $options['url'];
        }

        return $this->renderElement($label, $attribs);
    }

    private function renderLink(string $href, string $label, array $attribs) : string
    {
        return '<a href="' . $href . '"'
        . ' ' . $this->htmlAttribsToString($attribs) . '>'
        . $label
        . '</a>';
    }

    private function renderElement(string $label, array $attribs) : string
    {
        return '<span class="disqus-comment-count"'
            . ' ' . $this->htmlAttribsToString($attribs) . '>'
            . $label
            . '</span>';
    }

    public function visit(Code $code) : Code
    {
        $code->addScriptFile(self::SCRIPT_NAME);

        return $code;
    }
}
