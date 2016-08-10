<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare (strict_types=1);

namespace DisqusHelper\Tests;

use PHPUnit_Framework_TestCase;
use DisqusHelper\Widget\WidgetManager;
use DisqusHelper\Widget\ThreadWidget;
use DisqusHelper\Widget\CommentsCountWidget;
use DisqusHelper\Exception\InvalidWidgetConfigurationException;
use DisqusHelper\Exception\WidgetNotFoundException;
use DisqusHelper\Exception\InvalidWidgetException;
use DisqusHelper\Widget\WidgetInterface;

class WidgetManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingWidgetManager()
    {
        $widgetManager = WidgetManager::create([
            'thread' => ThreadWidget::class,
        ]);

        $this->assertTrue($widgetManager->has('thread'));
    }

    public function testCreatingWidgetManagerWithDefaultWidgets()
    {
        $widgetManager = WidgetManager::createWithDefaultWidgets();

        $this->assertTrue($widgetManager->has('thread'));
        $this->assertTrue($widgetManager->has('commentscount'));
    }

    public function testRegisteringWidget()
    {
        $widgetManager = WidgetManager::create([
            'thread' => ThreadWidget::class,
        ]);

        $widgetManager->registerWidget('commentsCount', CommentsCountWidget::class);

        $this->assertTrue($widgetManager->has('commentsCount'));
    }

    public function testHasWidgetReturnsFalseIfWidgetIsNotRegistered()
    {
        $widgetManager = WidgetManager::createWithDefaultWidgets();

        $this->assertFalse($widgetManager->has('nonregistered'));
    }

    public function testGettingWidgetRegisteredAsString()
    {
        $widgetManager = WidgetManager::create([
            'thread' => ThreadWidget::class,
        ]);

        $this->assertInstanceOf(ThreadWidget::class, $widgetManager->get('thread'));
    }

    public function testGettingWidgetRegisteredAsCallable()
    {
        $widgetManager = WidgetManager::create([
            'thread' => function () {
                return new ThreadWidget();
            },
        ]);

        $this->assertInstanceOf(ThreadWidget::class, $widgetManager->get('thread'));
    }

    public function testGettingWidgetRegisteredAsObject()
    {
        $threadWidget = new ThreadWidget();

        $widgetManager = WidgetManager::create([
            'thread' => $threadWidget,
        ]);

        $this->assertSame($threadWidget, $widgetManager->get('thread'));
    }

    public function testExceptionIsRaisedInCaseOfRegisteringInvalidWidgetConfiguration()
    {
        $this->expectException(InvalidWidgetConfigurationException::class);
        $this->expectExceptionMessage(
            "Invalid configuration for 'test' widget; widget should be either string, callable or " . WidgetInterface::class . " instance, integer given"
        );

        $widgetManager = WidgetManager::create([]);

        $widgetManager->registerWidget('test', 123);
    }

    public function testExceptionIsRaisedInCaseOfGettingNonRegisteredWidget()
    {
        $this->expectException(WidgetNotFoundException::class);
        $this->expectExceptionMessage("Unable to find 'nonregistered' widget");

        $widgetManager = WidgetManager::createWithDefaultWidgets();

        $widgetManager->get('nonregistered');
    }

    public function testExceptionIsRaisedInCaseOfGettingInvalidWidget()
    {
        $this->expectException(InvalidWidgetException::class);
        $this->expectExceptionMessage(sprintf(
            "Widget must implement %s interface",
            WidgetInterface::class
        ));

        $widgetManager = WidgetManager::create([
            'myWidget' => function () {
                return new class
 {
     public function getScriptName()
     {
         return 'test.js';
     }

     public function render(array $options = [])
     {
         return '<div id="my_widget"></div>';
     }
 };
            }
        ]);

        $widgetManager->get('myWidget');
    }
}
