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

use DisqusHelper\Disqus;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class DisqusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Disqus
     */
    private $disqus;

    protected function setUp()
    {
        $this->disqus = new Disqus('foobar');
    }

    public function testConfigRetrieval()
    {
        $disqus = new Disqus('foobar', array('title' => 'test'));

        $config = $disqus->getConfig();

        $this->assertInternalType('array', $config);
        $this->assertNotEmpty($config);
    }

    public function testShortnameIsPresentInConfig()
    {
        $disqus = new Disqus('foobar');

        $config = $disqus->getConfig();

        $this->assertArrayHasKey('shortname', $config);
        $this->assertEquals('foobar', $config['shortname']);
    }

    public function testConfigIsProperlySet()
    {
        $disqus = new Disqus('blog', array(
            'title' => 'My article',
            'identifier' => 'article1'
        ));

        $config = $disqus->getConfig();

        $this->assertEquals('blog', $config['shortname']);
        $this->assertEquals('My article', $config['title']);
        $this->assertEquals('article1', $config['identifier']);
    }

    /**
     * @expectedException \DisqusHelper\Exception\BadMethodCallException
     */
    public function testCannotInvokeUndefinedWidget()
    {
        $disqus = new Disqus('foobar');
        $disqus->undefined();
    }

    public function testWidgetRendering()
    {
        $disqus = new Disqus('foobar');

        $html = $disqus->thread();
        $this->assertInternalType('string', $html);
        $this->assertNotEmpty($html);
    }

    public function testConfigRenderedProperly()
    {
        $disqus = new Disqus('blog', array(
            'title' => 'My article',
            'identifier' => 'article1'
        ));

        $html = $disqus->thread();

        $html .= ' ' . $disqus();

        $this->assertContains('<script', $html);
        $this->assertContains('shortname', $html);
        $this->assertContains('blog', $html);
        $this->assertContains('title', $html);
        $this->assertContains('My article', $html);
        $this->assertContains('identifier', $html);
        $this->assertContains('article1', $html);
        $this->assertContains('</script>', $html);
    }

    public function testConfigSuppliedOnInvokeRenderedProperly()
    {
        $disqus = new Disqus('blog', array(
            'title' => 'My article',
            'identifier' => 'article1'
        ));

        $html = $disqus->thread();

        $html .= ' ' . $disqus(array(
            'title' => 'Article 2',
            'identifier' => 'article2'
        ));

        $this->assertContains('<script', $html);
        $this->assertContains('shortname', $html);
        $this->assertContains('blog', $html);
        $this->assertContains('title', $html);
        $this->assertContains('Article 2', $html);
        $this->assertContains('identifier', $html);
        $this->assertContains('article2', $html);
        $this->assertContains('</script>', $html);
    }

    public function testRenderingWidgetAssets()
    {
        $disqus = new Disqus('blog');

        $html = $disqus->thread();

        $html .= ' ' . $disqus();

        $this->assertContains('<script', $html);
        $this->assertContains(\DisqusHelper\Widget\Thread::SCRIPT_NAME, $html);
        $this->assertContains('</script>', $html);
    }

    public function testRenderingWidgetAssetsOnlyOnceRegardlessOfNumberOfWidgetInvokations()
    {
        $disqus = new Disqus('blog', array(
            'title' => 'My article',
            'identifier' => 'article1'
        ));

        $html = $disqus->thread();
        $html .= ' ' . $disqus->thread();

        $html .= ' ' . $disqus();

        $this->assertContains('<script', $html);
        $this->assertEquals(1, substr_count($html, \DisqusHelper\Widget\Thread::SCRIPT_NAME), 'Widget script rendered multiple times');
        $this->assertContains('</script>', $html);
    }

    /**
     * @expectedException \DisqusHelper\Exception\RuntimeException
     */
    public function testInitFailsIfCalledMoreThanOnce()
    {
        $disqus = new Disqus('foobar');

        $disqus->thread();

        $disqus();

        $disqus();
    }
}
