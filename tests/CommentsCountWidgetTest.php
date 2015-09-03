<?php
/**
 * This file is part of the DisqusHelper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper\Tests;

use DisqusHelper\Widget\CommentsCount as CommentsCountWidget;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class CommentsCountWidgetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommentsCountWidget
     */
    private $widget;

    protected function setUp()
    {
        $this->widget = new CommentsCountWidget();
    }

    protected function assertHtmlTag($html, $tagName, $value = null, array $attributes = array())
    {
        $this->assertStringStartsWith("<$tagName", $html);
        $this->assertStringEndsWith("</$tagName>", $html);

        if ($value) {
            $this->assertContains(">$value<", $html);
        }

        if (!empty($attributes)) {
            foreach ($attributes as $key => $val) {
                $this->assertRegexp('|' . preg_quote($key) . '\s?=\s?"' . preg_quote($val, '|') . '"|', $html);
            }
        }
    }

    /**
     * @expectedException \DisqusHelper\Exception\RuntimeException
     */
    public function testLinkRenderingFailsIfUrlIsMissing()
    {
        $this->widget->render(array('label' => 'Test'));
    }

    public function testRenderingLinkWithFragmentInHref()
    {
        $html = $this->widget->render(array('url' => 'http://example.com/article1.html'));

        $this->assertHtmlTag($html, 'a', null, array('href' => 'http://example.com/article1.html#disqus_thread'));
    }

    public function testRenderingLinkWithIdentifierAttribute()
    {
        $html = $this->widget->render(array(
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1'
        ));

        $this->assertHtmlTag($html, 'a', null, array('identifier' => 'article1'));
    }

    public function testRenderingLinkWithLabel()
    {
        $html = $this->widget->render(array(
            'url' => 'http://example.com/article1.html',
            'label' => 'Test'
        ));

        $this->assertHtmlTag($html, 'a', 'Test');
    }

    public function testRenderingSpanTag()
    {
        $html = $this->widget->render(array(
            'as_link' => false,
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1',
            'label' => 'Test'
        ));

        $this->assertHtmlTag($html, 'span', 'Test', array(
            'class' => 'disqus-comment-count',
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1'
        ));
    }
}
