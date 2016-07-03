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

        $this->assertStringStartsWith('<script>', $html);
        $this->assertContains("var disqus_config = function () {", $html);
        $this->assertContains("this.page.identifier = 'article1'", $html);
        $this->assertContains("this.page.category_id = 7", $html);
        $this->assertContains('var d = document, s = d.createElement("script");', $html);
        $this->assertContains('s.src = "//test.disqus.com/embed.js"', $html);
        $this->assertContains('(d.head || d.body).appendChild(s);', $html);
        $this->assertStringEndsWith('</script>', $html);
    }

    public function testRenderingHtmlWithoutConfiguration()
    {
        $code = Code::create('test')
            ->addScriptFile('count.js');

        $html = $code->toHtml();

        $this->assertStringStartsWith('<script>', $html);
        $this->assertNotContains("var disqus_config = function () {", $html);
        $this->assertContains('var d = document, s = d.createElement("script");', $html);
        $this->assertContains('s.src = "//test.disqus.com/count.js"', $html);
        $this->assertContains('(d.head || d.body).appendChild(s);', $html);
        $this->assertStringEndsWith('</script>', $html);
    }

    public function testToHtmlInvokedWhenCastingToString()
    {
        $code = Code::create('test')->addScriptFile('embed.js');

        $html = (string) $code;

        $this->assertStringStartsWith('<script>', $html);
        $this->assertContains('var d = document, s = d.createElement("script");', $html);
        $this->assertContains('s.src = "//test.disqus.com/embed.js"', $html);
        $this->assertContains('(d.head || d.body).appendChild(s);', $html);
        $this->assertStringEndsWith('</script>', $html);
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