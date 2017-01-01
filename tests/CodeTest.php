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

namespace DisqusHelper\Tests;

use PHPUnit_Framework_TestCase;
use DisqusHelper\Code;

class CodeTest extends PHPUnit_Framework_TestCase
{
    public function testMergingConfig()
    {
        $code = Code::create('test')
            ->mergeConfig(['var1' => 'test1', 'var2' => 'test2'])
            ->mergeConfig(['var1' => 'test']);

        $config = $code->getConfig();

        $this->assertEquals('test', $config['var1']);
    }

    public function testAddingScriptFile()
    {
        $code = Code::create('test')->addScriptFile('test.js');

        $this->assertTrue($code->hasScriptFile('test.js'));
    }

    public function testRenderingHtmlWithConfiguration()
    {
        $code = Code::create('test')
            ->mergeConfig([
                'page.identifier' => 'article1',
                'page.category_id' => 7,
            ])
            ->addScriptFile('embed.js');

        $html = $code->toHtml();

        $scripts = substr_count($html, '<script');
        $this->assertEquals(2, $scripts);
        $scripts = substr_count($html, '</script>');
        $this->assertEquals(2, $scripts);
        $this->assertContains("disqus_config", $html);
        $this->assertContains("page.identifier = 'article1'", $html);
        $this->assertContains("page.category_id = 7", $html);
        $this->assertContains('test.disqus.com/embed.js', $html);
    }

    public function testRenderingHtmlWithoutConfiguration()
    {
        $code = Code::create('test')->addScriptFile('count.js');

        $html = $code->toHtml();

        $scripts = substr_count($html, '<script');
        $this->assertEquals(1, $scripts);
        $scripts = substr_count($html, '</script>');
        $this->assertEquals(1, $scripts);
        $this->assertNotContains("disqus_config", $html);
        $this->assertContains('test.disqus.com/count.js', $html);
    }

    public function testRenderingLazyLoadedScriptFile()
    {
        $code = Code::create('test')->addScriptFile('embed.js', [
            'lazy_load' => true,
        ]);

        $html = $code->toHtml();

        $this->assertContains('<script>', $html);
        $this->assertContains('d.createElement("script")', $html);
        $this->assertContains('test.disqus.com/embed.js', $html);
    }

    public function testRenderingScriptFileWithId()
    {
        $code = Code::create('test')->addScriptFile('count.js', [
            'lazy_load' => false,
            'id' => 'identifier',
        ]);

        $html = $code->toHtml();

        $this->assertContains('<script id="identifier" src="//test.disqus.com/count.js" async></script>', $html);
    }

    public function testToHtmlInvokedWhenCastingToString()
    {
        $code = Code::create('test')->addScriptFile('embed.js');

        $html = (string) $code;

        $this->assertContains('test.disqus.com/embed.js', $html);
    }

    public function testSameJsFilesRenderedOnlyOnce()
    {
        $code = Code::create('test')
            ->addScriptFile('embed.js')
            ->addScriptFile('embed.js')
            ->addScriptFile('embed.js');

        $html = $code->toHtml();

        $this->assertEquals(1, substr_count($html, 'disqus.com/embed.js'), 'Same JS file rendered multiple times');
    }
}
