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

    public function testInitRendersConfig()
    {
        $disqus = new Disqus('blog', array(
            'title' => 'My article',
            'identifier' => 'article1'
        ));

        $html = $disqus->thread();

        $html .= ' ' . $disqus->init();

        $this->assertContains('<script', $html);
        $this->assertContains('shortname', $html);
        $this->assertContains('blog', $html);
        $this->assertContains('title', $html);
        $this->assertContains('My article', $html);
        $this->assertContains('identifier', $html);
        $this->assertContains('article1', $html);
        $this->assertContains('</script>', $html);
    }

    /**
     * @expectedException \DisqusHelper\Exception\RuntimeException
     */
    public function testInitFailsIfCalledMoreThanOnce()
    {
        $disqus = new Disqus('foobar');

        $disqus->thread();

        $disqus->init();

        $disqus->init();
    }
}
