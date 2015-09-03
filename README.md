# Disqus helper

PHP library which facilitates integration of [Disqus](https://disqus.com/) widgets.

## Installation

Install the library using [composer](http://getcomposer.org/). Add the following to your `composer.json`:

```json
{
    "require": {
        "nikolaposa/disqus-helper": "~1.0"
    }
}
```

Tell composer to download DisqusHelper by running `install` command:

```bash
$ php composer.phar install
```

## Usage

**Configuration**
```php
use DisqusHelper\Disqus;

$disqus = new Disqus('disqus_shortname', array(
    'title' => 'My article',
    'identifier' => 'article1'
));

```

**Template**
```php
<html>
    <head>
        <title>Blog</title>
    </head>

    <body>
        <article>
            <h1>My article</h1>
            <?php echo $disqus->commentsCount(array('url' => 'http://example.com/article1.html')); ?>

            <p>My article text</p>
        </article>

        <div>
            <h2>Comments:</h2>
            <?php echo $disqus->thread(); ?>
        </div>
    </body>
</html>
```
