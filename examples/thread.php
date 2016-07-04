<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

require __DIR__ . '/../vendor/autoload.php';

use DisqusHelper\Disqus;

$disqus = Disqus::create('blog');

$disqus->configure([
    'language' => 'en',
    'page.identifier' => 'article1',
    'page.title' => 'My article',
]);

echo $disqus->thread() . "\n\n";

echo $disqus->getCode();
