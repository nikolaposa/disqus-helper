<?php
require __DIR__ . '/../vendor/autoload.php';

use DisqusHelper\Disqus;

$disqus = new Disqus('blog');

echo $disqus->thread([], [
    'title' => 'My article',
    'identifier' => 'article1'
]) . "\n\n";

echo $disqus();
