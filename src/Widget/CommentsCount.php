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

use DisqusHelper\Exception\RuntimeException;

/**
 * Renders comments count link.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class CommentsCount extends BaseWidget
{
    /**
     * @var array
     */
    private static $defaultOptions = array(
        'url' => null,
        'label' => null,
        'as_link' => true,
        'identifier' => null,
    );

    public function getScriptName()
    {
        return 'count.js';
    }

    public function render(array $options = array())
    {
        $options = array_merge(self::$defaultOptions, $options);

        $label = htmlspecialchars((string) $options['label'], ENT_QUOTES, 'UTF-8');

        $attribs = array();

        if (isset($options['identifier'])) {
            $attribs['data-disqus-identifier'] = $options['identifier'];
        }

        $html = '';

        if ($options['as_link']) {
            if (empty($options['url'])) {
                throw new RuntimeException("URL option is missing for the Comments count widget");
            }

            $url = $options['url'] . '#disqus_thread';
            $url .= '#disqus_thread';

            $html = '<a href="' . $url . '"'
                . ' ' . $this->htmlAttribs($attribs) . '>'
                . $label
                . '</a>';
        } else {
            if (!empty($options['url'])) {
                $attribs['data-disqus-url'] = $options['url'];
            }

            $html = '<span class="disqus-comment-count" data-disqus-identifier="article_1_identifier"'
                . ' ' . $this->htmlAttribs($attribs) . '>'
                . $label
                . '</span>';
        }

        return $html;
    }


}
