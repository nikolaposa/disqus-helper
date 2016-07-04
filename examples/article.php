<?php
require __DIR__ . '/../vendor/autoload.php';

$config = [
    'shortname' => 'disqus_shortname',
];

$disqus = DisqusHelper\Disqus::create($config['shortname']);
?>

<html>
    <head>
        <title>My article</title>

        <?php
            $disqus->configure([
                'page.identifier' => 'article1',
                'page.title' => 'My article',
            ]);
        ?>
    </head>

    <body>
        <article>
            <h1>My article</h1>
            <?php echo $disqus->commentsCount(['identifier' => 'article1', 'as_link' => false]); ?>

            <p>My article content</p>
        </article>

        <div>
            <h2>Comments:</h2>
            <?php echo $disqus->thread(); ?>
        </div>

        <?php echo $disqus->getCode(); ?>
    </body>
</html>