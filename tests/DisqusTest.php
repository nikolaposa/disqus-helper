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

    public function testGettingConfig()
    {
        $disqus = Disqus::create('test', [
            'title' => 'My article',
            'identifier' => 'article1'
        ]);

        $config = $disqus->getConfig();

        $this->assertEquals('My article', $config['title']);
        $this->assertEquals('article1', $config['identifier']);
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

    public function testWidgetInvokeWithConfigValidation()
    {
        $this->expectException(InvalidArgumentException::class);

        $disqus = Disqus::create('test');

        $disqus->thread([], 'test');
    }

    public function testResultingCodeContainsConfigurationAndWidgetAssets()
    {
        $disqus = Disqus::create('test', [
            'title' => 'My article',
            'identifier' => 'article1'
        ]);

        $html = $disqus->thread();

        $html .= ' ' . $disqus->getCode();

        $this->assertContains('<script', $html);
        $this->assertContains('shortname', $html);
        $this->assertContains('test', $html);
        $this->assertContains('title', $html);
        $this->assertContains('My article', $html);
        $this->assertContains('identifier', $html);
        $this->assertContains('article1', $html);
        $this->assertContains(ThreadWidget::SCRIPT_NAME, $html);
        $this->assertContains('</script>', $html);
    }

    public function testWidgetAssetsDoNotRepeatInResultingCodeRegardlessOfNumberOfInvokations()
    {
        $disqus = Disqus::create('test', [
            'title' => 'My article',
            'identifier' => 'article1'
        ]);

        $html = $disqus->thread();
        $html .= ' ' . $disqus->thread();

        $html .= ' ' . $disqus->getCode();

        $this->assertContains('<script', $html);
        $this->assertEquals(1, substr_count($html, ThreadWidget::SCRIPT_NAME), 'Widget script rendered multiple times');
        $this->assertContains('</script>', $html);
    }

    public function testGetCodeInvokedWhenCastingToString()
    {
        $disqus = Disqus::create('test', [
            'title' => 'My article',
        ]);

        $html = (string) $disqus;

        $this->assertContains('<script', $html);
        $this->assertContains('shortname', $html);
        $this->assertContains('test', $html);
        $this->assertContains('title', $html);
        $this->assertContains('My article', $html);
        $this->assertContains('</script>', $html);
    }
}
