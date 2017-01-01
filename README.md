# Disqus Helper

[![Build Status](https://travis-ci.org/nikolaposa/disqus-helper.svg?branch=master)](https://travis-ci.org/nikolaposa/disqus-helper)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/disqus-helper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/disqus-helper/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/disqus-helper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/disqus-helper/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/disqus-helper/v/stable)](https://packagist.org/packages/nikolaposa/disqus-helper)

PHP library which facilitates integration of [Disqus](https://disqus.com/) widgets.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following
command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/disqus-helper
```

## Usage

**Initialization**
```php
use DisqusHelper\Disqus;

$disqus = Disqus::create('disqus_shortname');

```

**Template**
```html
<html>
    <head>
        <title>Blog</title>

        <?php
            //Page-specific Disqus configuration
            $disqus->configure([
                'page.identifier' => 'article1',
                'page.title' => 'My article',
            ]);
        ?>
    </head>

    <body>
        <article>
            <h1>My article</h1>
            <!-- Comments count widget -->
            <?php echo $disqus->commentsCount(['url' => 'http://example.com/article1.html']); ?>

            <p>My article text</p>
        </article>

        <div>
            <h2>Comments:</h2>
            <!-- Thread widget -->
            <?php echo $disqus->thread(); ?>
        </div>

        <!-- MUST be called at the end, usually before closing </body> tag -->
        <?php echo $disqus->getCode(); ?>
    </body>
</html>
```

See [more examples](https://github.com/nikolaposa/disqus-helper/tree/master/examples).

## Author

**Nikola Poša**

* https://twitter.com/nikolaposa
* https://github.com/nikolaposa

## Copyright and license

Copyright 2017 Nikola Poša. Released under MIT License - see the `LICENSE` file for details.