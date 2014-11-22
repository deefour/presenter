# Presenter

[![Build Status](https://travis-ci.org/deefour/presenter.svg)](https://travis-ci.org/deefour/presenter)
[![Packagist Version](http://img.shields.io/packagist/v/deefour/presenter.svg)](https://packagist.org/packages/deefour/presenter)
[![Code Climate](https://codeclimate.com/github/deefour/presenter/badges/gpa.svg)](https://codeclimate.com/github/deefour/presenter)

Object-oriented presentation logic.

## Getting Started

Add Presenter to your `composer.json` file and run `composer update`. See [Packagist](https://packagist.org/packages/deefour/presenter) for specific versions.

```
"deefour/presenter": "~0.1@dev"
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

class Article {

  public $published = true;

  public $title = 'Governor Mike Taylor Runs for Second Term';

  public function isDraft() {
    return ! $this->published;
  }

}
```

The factory is unwilling to attempt presenter instantiaion for classes that do not implement `Deefour\Presenter\Contracts\PresentableContract`. A `Deefour\Presenter\Traits\Presentable` trait is available to satisfy the interface with sensible defaults.

```php
namespace App;

use Deefour\Presenter\Contracts\PresentableContract;
use Deefour\Presenter\Traits\Presentable;

class Article implements PresentableContract {

  use Presentable;

  /**
   * @inheritdoc
   */
  public function presenterNamespace() {
    return '\\App\\Presenters';
  }

  // properties and methods here ...

}
```

> **Note:** The `presenterNamespace()` method tells the factory where to look for the `ArticlePolicy`, pointing it at `App\Presenters\ArticlePolicy` instead of the default `ArticlePolicy`.

## Presenters

As for the `ArticlePresenter`, a bare implementation could be

```php
namespace App\Presenters;

use Deefour\Presenter\AbstractPresenter;

class ArticlePresenter extends AbstractPresenter {

  public function isDraft() {
    return $this->model->isDraft() ? 'Yes' : 'No';
  }

}

```

### The API

A quick overview of the API available.

```php
use Deefour\Presenter\Factory;

$presenter = (new Factory)->make(new Article); //=> ArticlePolicy

$presenter->model; //=> Article

$presenter->model->isDraft(); //=> false
$presenter->isDraft(); //=> 'No'
$presenter->is_draft; //=> 'No'

$presenter->model()->published; //=> true
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

class Article {

  public function category() {
    return new Category;
  }

  public function tags() {
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
$presenter->model->tags()->first(); //=> Tag
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

A `presenter()`  global function is available to generate a presenter for an object anywhere within an application. In a view, the following could be done

```php
presenter($article)->is_draft; //=> 'No'
```

### Middleware

A middleware exists at `Deefour\Presenter\Middleware\DecoratePresentableObjects` that will attempt to decorate every variable passed to a view as part of a rendered response with it's related presenter.

To enable this middleware, add the FQCN to the list of in `app/Http/Kernel.php`.

```php
protected $middleware = [

  // ...

  'Deefour\Presenter\Middleware\DecoreatePresentableObjects',

];
```

> **Note:** This middleware is **completely optional**. It may save a few keystrokes controller actions and views, but you should decide carefully if using such a middleware is the smartest approach to decorate objects for presentation within *your* application.


## Contribute

- Issue Tracker: https://github.com/deefour/presenter/issues
- Source Code: https://github.com/deefour/presenter

## Changelog

#### 0.1.0 - November 21, 2014

 - Initial release

## License

Copyright (c) 2014 [Jason Daly](http://www.deefour.me) ([deefour](https://github.com/deefour)). Released under the [MIT License](http://deefour.mit-license.org/).