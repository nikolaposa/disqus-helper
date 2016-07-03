<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper\Tests;

use PHPUnit_Framework_TestCase;
use DisqusHelper\Widget\CommentsCountWidget;
use DisqusHelper\Exception\RuntimeException;
use DisqusHelper\Code;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class CommentsCountWidgetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CommentsCountWidget
     */
    private $widget;

    protected function setUp()
    {
        $this->widget = new CommentsCountWidget();
    }

    protected function assertHtmlTag($html, $tagName, $value = null, array $attributes = [])
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

    public function testLinkRenderingFailsIfUrlIsMissing()
    {
        $this->expectException(RuntimeException::class);

        $this->widget->render(['label' => 'Test']);
    }

    public function testRenderingLinkWithFragmentInHref()
    {
        $html = $this->widget->render(['url' => 'http://example.com/article1.html']);

        $this->assertHtmlTag($html, 'a', null, ['href' => 'http://example.com/article1.html#disqus_thread']);
    }

    public function testRenderingLinkWithIdentifierAttribute()
    {
        $html = $this->widget->render([
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1'
        ]);

        $this->assertHtmlTag($html, 'a', null, ['identifier' => 'article1']);
    }

    public function testRenderingLinkWithLabel()
    {
        $html = $this->widget->render([
            'url' => 'http://example.com/article1.html',
            'label' => 'Test'
        ]);

        $this->assertHtmlTag($html, 'a', 'Test');
    }

    public function testRenderingSpanTag()
    {
        $html = $this->widget->render([
            'as_link' => false,
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1',
            'label' => 'Test'
        ]);

        $this->assertHtmlTag($html, 'span', 'Test', [
            'class' => 'disqus-comment-count',
            'url' => 'http://example.com/article1.html',
            'identifier' => 'article1'
        ]);
    }

    public function testVisitingCodeAddsJsFile()
    {
        $code = Code::create();

        $code = $this->widget->visit($code);

        $this->assertTrue($code->hasScriptFile(CommentsCountWidget::SCRIPT_NAME));
    }
}
