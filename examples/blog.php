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

$config = [
    'shortname' => 'disqus_shortname',
];

$disqus = DisqusHelper\Disqus::create($config['shortname']);
?>

<!DOCTYPE html>
<html>
    <body>

    <h1>Blog</h1>

    <dl>
        <dt>Article 1 (<?= $disqus->commentsCount(['identifier' => 'article1', 'url' => 'http://example.com/article1.html']); ?>)</dt>
        <dd>some description</dd>

        <dt>Article 2 (<?= $disqus->commentsCount(['identifier' => 'article2', 'url' => 'http://example.com/article2.html']); ?>)</dt>
        <dd>some description</dd>
    </dl>

    <?= $disqus->getCode(); ?>
    </body>
</html>
