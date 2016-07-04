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
    'shortname' => 'nikolaposa',
];

$disqus = DisqusHelper\Disqus::create($config['shortname']);

$disqus->configure([
    'language' => 'en',
    'page.identifier' => 'article2',
    'page.title' => 'Article 2',
]);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Article 2</title>
    </head>

    <body>
        <article>
            <h1>Article 2</h1>

            <p>some content</p>
        </article>

        <div>
            <h2>Comments:</h2>
            <?= $disqus->thread(); ?>
        </div>

        <?= $disqus->getCode(); ?>
    </body>
</html>
