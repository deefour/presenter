# Presenter

[![Build Status](https://travis-ci.org/deefour/presenter.svg)](https://travis-ci.org/deefour/presenter)
[![Packagist Version](http://img.shields.io/packagist/v/deefour/presenter.svg)](https://packagist.org/packages/deefour/presenter)
[![Code Climate](https://codeclimate.com/github/deefour/presenter/badges/gpa.svg)](https://codeclimate.com/github/deefour/presenter)
[![License](https://poser.pugx.org/deefour/presenter/license.svg)](https://packagist.org/packages/deefour/presenter)

Object-oriented presentation logic.

## Getting Started

Run the following to add Presenter to your project's `composer.json`. See [Packagist](https://packagist.org/packages/deefour/presenter) for specific versions.

```bash
composer require deefour/presenter
```

**`>=PHP5.5.0` is required.**

## The Presenter Factory

A factory class is available to resolve the FQCN for or instantiate an instance of a presenter class associated with the passed presentable object.

```php
use Deefour\Presenter\Factory;

(new Factory)->resolve(new Article);          //=> 'ArticlePresenter'
(new Factory)->make(new Article);             //=> 'ArticlePresenter'
(new Factory)->makeOrFail(new Article);       //=> 'ArticlePresenter'
(new Factory)->makeOrFail(new InvalidObject); //=> throws 'NotPresentableException'
```

#### Preparing Models for Presentation

Given an `Article` object

```php
namespace App;

class Article
{
    public $published = true;

    public $title = 'Governor Mike Taylor Runs for Second Term';

    public function isDraft()
    {
        return ! $this->published;
    }
}
```

The factory is unwilling to attempt presenter instantiaion for classes that do not implement `Deefour\Presenter\Contracts\Presentable`. A `Deefour\Presenter\ProducesPresenters` trait is available to satisfy the interface.

```php
namespace App;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\ProducesPresenters;

class Article implements Presentable
{
    use ProducesPresenters;

    // ...
}
```

By default, the factory will resolve `'App\ArticlePresenter'`. A `resolve` method can be added to the class to provide custom logic.

```php
namespace App;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\ProducesPresenters;

class Article implements Presentable
{
    use ProducesPresenters;

    public function resolve()
    {
        return $this->published ? 'App\\PublishedArticlePresenter' : 'App\\ArticlePresenter';
    }
}
```

**Note:** When using this package with [`deefour/authorizer`](https://github.com/deefour/authorizer) or [`deefour/producer`](https://github.com/deefour/producer), care must be taken when overriding the `resolve()` method to account for policies, scopes, and other classes that may be resolved through the production factory in `deefour/producer`.

## Presenters

As for the `ArticlePresenter` itself, a bare implementation could be

```php
namespace App\Presenters;

use Deefour\Presenter\Presenter;

class ArticlePresenter extends Presenter
{
    public function isDraft()
    {
        return $this->_model->isDraft() ? 'Yes' : 'No';
    }
}

```

### The API

A quick overview of the API available.

```php
use Deefour\Producer\Factory;

$presenter = (new Factory)->make(new Article, 'presenter'); //=> ArticlePolicy

$presenter->_model; //=> Article

$presenter->_model->isDraft(); //=> false
$presenter->isDraft(); //=> 'No'
$presenter->is_draft; //=> 'No'

$presenter->_model()->published; //=> true
$presenter->published; //=> true
```

A few things to notice:

 - The underlying object decorated by the presenter can be accessed via the `$model` property or `model()` method.
 - Any property or method publicly accessible on the underlying object can also be accessed directly through the presenter.
 - Any publicly accessible, camel-cased method on the presenter or underlying model can be accessed via snake-cased property access.

### Automatic Presenter Resolution

If a property or method is resolved through the `__get()` or `__call()` methods on the presenter, an attempt will be made to resolve and wrap the return value in a related presenter.

```php
namespace App;

use Illuminate\Support\Collection;

class Article
{
    public function category()
    {
        return new Category;
    }

    public function tags()
    {
        $collection = Collection;

        $collection->push(new Tag);
        $collection->push(new Tag);
        $collection->push(new Tag);

        return $collection;
    }
}
```

Given the existence of `ArticlePresenter`, `CategoryPresenter`, and `TagPresenter`, the following will be returned

```php
use Deefour\Presenter\Factory;

$presenter = (new Factory)->make(new Article, 'presenter'); //=> ArticlePresenter

$presenter->category; //=> CategoryPresenter
$presenter->tags->first(); //=> TagPresenter
```

> **Note:** The collection resolution works by looking for an instance of `IteratorAggregate`. The iterator is used to loop through the collection and generate presenters for each item. An attempt is then made to instantiate a new instance of the original object implementing `IteratorAggregate`. **That** is the return value.

If you want access to the raw association, simply request it from the underlying object.

```php
$presenter->_model->tags()->first(); //=> Tag
```

### Helper Methods

A global `present()` function can be made available by including the `helpers.php` file in your project's `composer.json`. Presenter doesn't autoload this file, giving you the choice whether or not to 'pollute' the global environment with this function.

```php
"autoload": {
  "psr-4": {
    ...
  },
  "files": [
    "vendor/deefour/presenter/src/Presenter/helpers.php"
  ]
}
```

In a view, the following could be done

```php
present($article)->is_draft; //=> 'No'
```

## Contribute

- Issue Tracker: https://github.com/deefour/presenter/issues
- Source Code: https://github.com/deefour/presenter

## Changelog

#### 0.7.3 - August 8, 2015

 - Compat changes for updates to [`deefour/producer`](https://github.com/deefour/producer).
 - New `Factory` class is available to prevent the need to interact directly with the factory in `deefour/producer`.

#### 0.7.2 - August 4, 2015

 - Abstracted presenter resolution out to new [`deefour/producer`](https://github.com/deefour/producer).
 - Removed the Laravel service provider and facade. The `'producer'` service in `deefour/producer` should be used instead.

#### 0.6.2 - June 5, 2015

 - Now following PSR-2.

#### 0.6.0 - May 24, 2015

 - Removed `model()` method on base presenter.
 - Renamed `$model` property to `$_model` to avoid conflicts with an actual model
 attribute with the name `'model'`.
 - Presenters now only provide property access to **public** properties on the
 presenter.
 - Prefixed API methods/properties with `_` on the base presenter to further avoid
 conflicts with attribute overrides.
 - Made `$_model` property public.
 - Updates to code formatting.

#### 0.5.0 - April 27, 2015

 - Snake-case to camel-case method conversions are now cached for performance
 - Exceptions are no longer thrown for missing properties/methods. See [`6f33dda`](https://github.com/deefour/presenter/commit/6f33dda7f310d95646091e6e5392ffd66d81ba00) for an explanation.

#### 0.4.0 - March 19, 2015

 - Rename `presenter()` helper to `present()`
 - Remove `helpers.php` from Composer autoload. Developers should be able to choose whether these functions are included.
 - Cleaning docblocks.
 - Type-hinting the presenter factory.
 - Renaming `Presentable` trait to `ResolvesPresenters` to avoid naming conflict with `\Deefour\Presenter\Contracts\Presentable`.

#### 0.3.0 - March 16, 2015

 - Allow presenters to be explicitly requested, bypassing the model default. For example
     ```php
       $article = new Article;
       echo get_class($article->presenter()); //=> 'ArticlePresenter'
       echo get_class($article->presenter(FeaturedArticlePresenter::class)); //=> 'FeaturedArticlePresenter'
     ```

#### 0.2.3 - February 27, 2015

 - `Illuminate\Support\Collection` instances and native PHP arrays can now be passed directly into the `presenter()` helper.

#### 0.2.2 - February 20, 2015

 - Updated support for Laravel's Eloquent relations. Relations are now fetched and converted to presenter-wrapped objects or collections when requested.

#### 0.2.0 - February 5, 2015

 - Fix service provider.
 - Make global `presenter()` work with Laravel IoC container if it's available.
 - Move trait.

#### 0.1.0 - November 21, 2014

 - Initial release.

## License

Copyright (c) 2014 [Jason Daly](http://www.deefour.me) ([deefour](https://github.com/deefour)). Released under the [MIT License](http://deefour.mit-license.org/).
