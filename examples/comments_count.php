<?php
require __DIR__ . '/../vendor/autoload.php';

use DisqusHelper\Disqus;

$disqus = new Disqus('blog');

echo $disqus->commentsCount(['url' => 'http://example.com/article1.html']) . "\n\n";
echo $disqus->commentsCount(['url' => 'http://example.com/article1.html', 'as_link' => false]) . "\n\n";

echo $disqus();
