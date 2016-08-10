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

require __DIR__ . '/../vendor/autoload.php';

$config = [
    'shortname' => 'disqus_shortname',
];

$disqus = DisqusHelper\Disqus::create($config['shortname']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Article 1</title>

        <?php
            $disqus->configure([
                'page.identifier' => 'article1',
                'page.title' => 'Article 1',
            ]);
        ?>
    </head>

    <body>
        <article>
            <h1>Article 1</h1>
            <?= $disqus->commentsCount(['identifier' => 'article1', 'as_link' => false]); ?>

            <p>some content</p>
        </article>

        <div>
            <h2>Comments:</h2>
            <?= $disqus->thread(); ?>
        </div>

        <?= $disqus->getCode(); ?>
    </body>
</html>