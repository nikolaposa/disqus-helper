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

echo $disqus->thread([], [
    'title' => 'My article',
    'identifier' => 'article1'
]) . "\n\n";

echo $disqus();
