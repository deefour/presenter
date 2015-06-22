# Presenter

[![Build Status](https://travis-ci.org/deefour/presenter.svg)](https://travis-ci.org/deefour/presenter)
[![Packagist Version](http://img.shields.io/packagist/v/deefour/presenter.svg)](https://packagist.org/packages/deefour/presenter)
[![Code Climate](https://codeclimate.com/github/deefour/presenter/badges/gpa.svg)](https://codeclimate.com/github/deefour/presenter)
[![License](https://poser.pugx.org/deefour/presenter/license.svg)](https://packagist.org/packages/deefour/presenter)

Object-oriented presentation logic.

## Getting Started

Add Presenter to your `composer.json` file and run `composer update`. See [Packagist](https://packagist.org/packages/deefour/presenter) for specific versions.

```
"deefour/presenter": "~0.6.2"
```

**`>=PHP5.5.0` is required.**

## The Presenter Factory

A factory is available at `Deefour\Presenter\Factory` to instantiate presenters based on objects passed on.

### Class Resolution

The `resolve()` method will generate a FQCN for the passed object. No check is performed here to ensure a class actually exists with the returned name.

```php
use Deefour\Presenter\Factory;

(new Factory)->resolve(new Article); //=> "ArticlePresenter"
```

### Presenter Instantiation

The `make()` method will attempt to instantiate the resolved FQCN for the passed object. If no matching presenter class exists, `null` will be returned.

```php
use Deefour\Presenter\Factory;

(new Factory)->make(new Article); //=> ArticlePresenter
```

> **Note:** There is a similar `makeOrFail()` method that will throw an exception if the presenter class does not exist.

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

The factory is unwilling to attempt presenter instantiaion for classes that do not implement `Deefour\Presenter\Contracts\Presentable`. A `Deefour\Presenter\ResolvesPresenters` trait is available to satisfy the interface with sensible defaults.

```php
namespace App;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\ResolvesPresenters;

class Article implements Presentable
{
    use ResolvesPresenters;

    /**
     * @inheritdoc
     */
    public function presenterNamespace()
    {
        return '\App\Presenters';
    }

    // properties and methods here ...
}
```

> **Note:** The `presenterNamespace()` method tells the factory where to look for the `ArticlePolicy`, pointing it at `App\Presenters\ArticlePolicy` instead of the default `ArticlePolicy`.

## Presenters

As for the `ArticlePresenter`, a bare implementation could be

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
use Deefour\Presenter\Factory;

$presenter = (new Factory)->make(new Article); //=> ArticlePolicy

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

$presenter = (new Factory)->make(new Article); //=> ArticlePolicy

$presenter->category; //=> CategoryPresenter
$presenter->tags->first(); //=> TagPresenter
```

> **Note:** The collection resolution works by looking for an instance of `IteratorAggregate`. The iterator is used to loop through the collection and generate presenters for each item. An attempt is then made to instantiate a new instance of the original object implementing `IteratorAggregate`. **That** is the return value.

If you want access to the raw association, simply request it from the underlying object.

```php
$presenter->_model->tags()->first(); //=> Tag
```

## Integration with Laravel

Presenter comes with a service provider for the `Deefour\Presenter\Factory` presenter factory. In Laravel's `config/app.php` file, add the `PresenterServiceProvider` to the list of providers.

```php
'providers' => [

  // ...

  'Deefour\Presenter\Providers\PresenterServiceProvider',

],
```

### Helper Methods

A global `present()` function can be made globally available by including the `helpers.php` file in your project's `composer.json`. Presenter doesn't autoload this file, giving you the choice whether or not to 'pollute' the global environment with this function.

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
