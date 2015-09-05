<?php
require __DIR__ . '/../vendor/autoload.php';

use DisqusHelper\Disqus;

$disqus = new Disqus('blog');

echo $disqus->thread() . "\n\n";

echo $disqus(array(
    'title' => 'My article',
    'identifier' => 'article1'
));
