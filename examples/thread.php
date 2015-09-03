<?php
require __DIR__ . '/../vendor/autoload.php';

use DisqusHelper\Disqus;

$disqus = new Disqus('blog', array(
    'title' => 'My article',
    'identifier' => 'article1'
));

echo $disqus->thread() . "\n\n";

echo $disqus->init();
