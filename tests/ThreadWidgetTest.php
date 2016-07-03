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
use DisqusHelper\Widget\ThreadWidget;
use DisqusHelper\Code;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ThreadWidgetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ThreadWidget
     */
    private $widget;

    protected function setUp()
    {
        $this->widget = new ThreadWidget();
    }

    public function testRendering()
    {
        $html = $this->widget->render();

        $this->assertContains('disqus_thread', $html);
    }

    public function testVisitingCodeAddsJsFile()
    {
        $code = Code::create('test');

        $code = $this->widget->visit($code);

        $this->assertTrue($code->hasScriptFile(ThreadWidget::SCRIPT_NAME));
    }
}
