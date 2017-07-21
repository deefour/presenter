# Presenter

<a href="https://travis-ci.org/deefour/presenter"><img src="https://travis-ci.org/deefour/presenter.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/deefour/presenter"><img src="https://poser.pugx.org/deefour/presenter/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/deefour/presenter"><img src="https://poser.pugx.org/deefour/presenter/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/deefour/presenter"><img src="https://poser.pugx.org/deefour/presenter/license.svg" alt="License"></a>

Object-oriented presentation logic.

## Getting Started

Run the following to add Presenter to your project's `composer.json`. See [Packagist](https://packagist.org/packages/deefour/presenter) for specific versions.

```bash
composer require deefour/presenter
```

**`>=PHP5.5.0` is required.**

### The Resolver

The `Deefour\Presenter\Resolver` determines the FQN of a presenter class associated with an object. The default behavior of the resolver is to append `'Presenter'` to the `Article` FQN.

```php
use Deefour\Presenter\Resolver;

(new Resolver)->presenter(new Article); //=> 'ArticlePresenter'
```

This behavior can be customized by passing a callable to the resolver.

```php
use Deefour\Presenter\Resolver;

$resolver = new Resolver;

$resolver->resolveWith(function ($instance) {
  return "App\Presenters\" . get_class($instance) . 'Presenter';
});

$resolver->presenter(new Article); //=> 'App\Presenters\ArticlePresenter'
```

The resolver will look for a `modelClass()` method on an object. If found, the returned FQN will be used instead of the object itself.


```php
class BlogPost
{
  static public function modelClass()
  {
      return Article::class;
  }
}

(new Resolver)->presenter(new BlogPost); //=> 'ArticlePresenter'
```

### Instantiation

Instantiating an instance of a presenter is your responsibility.

```php
use Deefour\Presenter\Resolver;

$article       = new Article;
$presenterName = (new Resolver)->presenter($article);
$presenter     = new $presenterName($article);
```

```php
use Deefour\Presenter\Resolver;

(new Resolver)->presenter(new Article); //=> 'BlogPresenter'
```

If the resulting FQN from the resolver does not match an existing, valid class name, `null` will be returned or a `NotDefinedException` will be thrown.

```php
use Deefour\Presenter\Resolver;

(new Resolver)->presenter(new ObjectWithoutPresenter); //=> null
(new Resolver)->presenterOrFail(new ObjectWithoutPresenter); //=> throws NotDefinedException
```

## Presenters

The presenters themselves extend `Deefour\Presenter\Presenter`.

```php
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

 - The underlying object decorated by the presenter can be accessed via the `$_model` property or `model()` method.
 - Any property or method publicly accessible on the underlying object can also be accessed directly through the presenter.
 - Any publicly accessible, camel-cased method on the presenter or underlying model can be accessed via snake-cased property access.

### Automatic Presenter Resolution

When a property or method is resolved through the `__get()` or `__call()` methods on the presenter, an attempt will be made to resolve and wrap the return value in a presenter too.

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
        $collection = new Collection;

        $collection->push(new Tag);
        $collection->push(new Tag);
        $collection->push(new Tag);

        return $collection;
    }
}
```

Given the existence of `ArticlePresenter`, `CategoryPresenter`, and `TagPresenter`, the following will be returned

```php
use Deefour\Presenter\Resolver;

$presenter = (new Resolver)->presenter(new Article); //=> ArticlePresenter

(new $presenter)->category;      //=> CategoryPresenter
(new $presenter)->tags->first(); //=> TagPresenter
```

> **Note:** The collection resolution works by looking for an instance of `IteratorAggregate`. The iterator is used to loop through the collection and generate presenters for each item. An attempt is then made to instantiate a new instance of the original object implementing `IteratorAggregate`. **That** is the return value.

If you want access to the raw association, simply request it from the underlying object.

```php
$presenter->_model->tags()->first(); //=> Tag
```

## Contribute

- Issue Tracker: https://github.com/deefour/presenter/issues
- Source Code: https://github.com/deefour/presenter

## Changelog

### 3.0.0 - July 20, 2017

 - The resolver no longer accepts an object during instantiation. Instead, objects are passed directly to the `presenter()` and `presenterOrFail()` methods.
 - A new `resolveWith()` method on the resolver accepts a callable to customize resolution.
 - Support for the `presenterClass()` has been removed from the resolver in favor of the new `resolveWith()` method *on* the resolver. You can pass a callable with your existing `presenterClass()` logic to the resolver instead.
 - Model access should only be done through the new `model()` method. Access to `_model` has been disabled.

#### 2.0.0 - February 12, 2017

 - Replaced `Factory` with new `Resolver` class.
 - Removed dependency on [`deefour\producer`](https://github.com/deefour/producer)
 - Removed `Presentable` contract
 - Simplified `README.md`

#### 1.0.0 - October 7, 2015

 - Release 1.0.0.

#### 0.8.0 - August 8, 2015

 - Compat changes for updates to [`deefour/producer`](https://github.com/deefour/producer).
 - New `Factory` class is available to prevent the need to interact directly with the factory in `deefour/producer`.
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
