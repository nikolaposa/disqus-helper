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
    public function testSettingSingleConfigVariable()
    {
        $code = Code::create()->setConfigVariable('foo', 'bar');

        $this->assertEquals('bar', $code->getConfigVariable('foo'));
    }

    public function testMergingConfig()
    {
        $code = Code::create()
            ->setConfigVariable('var1', 'test1')
            ->setConfigVariable('var2', 'test2')
            ->mergeConfig(['var1' => 'test']);

        $this->assertEquals('test', $code->getConfigVariable('var1'));
    }

    public function testAddingScriptFile()
    {
        $code = Code::create()->addScriptFile('test.js');

        $this->assertTrue($code->hasScriptFile('test.js'));
    }

    public function testRenderingHtml()
    {
        $code = Code::create()
            ->mergeConfig([
                'shortname' => 'test',
                'title' => 'My article',
                'identifier' => 'article1'
            ])
            ->addScriptFile('embed.js');

        $html = $code->toHtml();

        $this->assertStringStartsWith('<script type="text/javascript">', $html);
        $this->assertContains("var disqus_shortname = 'test';", $html);
        $this->assertContains("var disqus_title = 'My article'", $html);
        $this->assertContains("var disqus_identifier = 'article1'", $html);
        $this->assertContains('var s = document.createElement("script");', $html);
        $this->assertContains('s.type = "text/javascript"', $html);
        $this->assertContains('s.async = true', $html);
        $this->assertContains('s.src = "//" + disqus_shortname + ".disqus.com/embed.js"', $html);
        $this->assertContains('(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(s);', $html);
        $this->assertStringEndsWith('</script>', $html);
    }

    public function testToHtmlInvokedWhenCastingToString()
    {
        $code = Code::create()
            ->mergeConfig([
                'shortname' => 'test',
                'title' => 'My article',
                'identifier' => 'article1'
            ])
            ->addScriptFile('embed.js');

        $html = (string) $code;

        $this->assertStringStartsWith('<script type="text/javascript">', $html);
        $this->assertContains("var disqus_shortname = 'test';", $html);
        $this->assertContains("var disqus_title = 'My article'", $html);
        $this->assertContains("var disqus_identifier = 'article1'", $html);
        $this->assertContains('var s = document.createElement("script");', $html);
        $this->assertContains('s.type = "text/javascript"', $html);
        $this->assertContains('s.async = true', $html);
        $this->assertContains('s.src = "//" + disqus_shortname + ".disqus.com/embed.js"', $html);
        $this->assertContains('(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(s);', $html);
        $this->assertStringEndsWith('</script>', $html);
    }

    public function testSameJsFilesRenderedOnlyOnce()
    {
        $code = Code::create()
            ->addScriptFile('embed.js')
            ->addScriptFile('embed.js')
            ->addScriptFile('embed.js');

        $html = $code->toHtml();

        $this->assertEquals(1, substr_count($html, 'disqus.com/embed.js'), 'Same JS file rendered multiple times');
    }
}