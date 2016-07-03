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
use DisqusHelper\Disqus;
use DisqusHelper\Exception\InvalidArgumentException;
use DisqusHelper\Exception\WidgetNotFoundException;
use DisqusHelper\Widget\ThreadWidget;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class DisqusTest extends PHPUnit_Framework_TestCase
{
    public function testGettingShortName()
    {
        $disqus = Disqus::create('test');

        $this->assertEquals('test', $disqus->getShortName());
    }

    public function testCannotInvokeUndefinedWidget()
    {
        $this->expectException(WidgetNotFoundException::class);

        $disqus = Disqus::create('test');
        $disqus->undefined();
    }

    public function testWidgetRendering()
    {
        $disqus = Disqus::create('test');

        $html = $disqus->thread();
        $this->assertInternalType('string', $html);
        $this->assertNotEmpty($html);
    }

    public function testWidgetInvokeOptionsValidation()
    {
        $this->expectException(InvalidArgumentException::class);

        $disqus = Disqus::create('test');

        $disqus->thread('test');
    }

    public function testResultingCodeContainsConfigurationAndWidgetAssets()
    {
        $disqus = Disqus::create('test');

        $disqus->configure([
            'page.identifier' => 'article1',
            'page.title' => 'My article',
        ]);

        $html = $disqus->thread();

        $html .= ' ' . $disqus->getCode();

        $this->assertContains('page.identifier', $html);
        $this->assertContains('article1', $html);
        $this->assertContains('page.title', $html);
        $this->assertContains('My article', $html);
        $this->assertContains(ThreadWidget::SCRIPT_NAME, $html);
    }

    public function testWidgetAssetsDoNotRepeatInResultingCodeRegardlessOfNumberOfInvokations()
    {
        $disqus = Disqus::create('test');

        $html = $disqus->thread();
        $html .= ' ' . $disqus->thread();

        $html .= ' ' . $disqus->getCode();

        $this->assertEquals(1, substr_count($html, ThreadWidget::SCRIPT_NAME), 'Widget script rendered multiple times');
    }

    public function testGetCodeInvokedWhenCastingToString()
    {
        $disqus = Disqus::create('test');

        $disqus->configure([
            'page.identifier' => 'article1',
        ]);

        $html = (string) $disqus;

        $this->assertContains('page.identifier', $html);
        $this->assertContains('article1', $html);
    }
}
