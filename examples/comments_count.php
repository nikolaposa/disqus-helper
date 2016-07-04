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

echo $disqus->commentsCount([
    'identifier' => 'article1',
    'url' => 'http://example.com/article1.html'
]) . "\n\n";

echo $disqus->commentsCount([
    'identifier' => 'article1',
    'url' => 'http://example.com/article2.html',
    'as_link' => false
]) . "\n\n";

echo $disqus->getCode();
