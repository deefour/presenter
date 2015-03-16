<?php

use Deefour\Presenter\Factory;
use Deefour\Presenter\Contracts\Presentable as PresentableContract;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;



if ( ! function_exists('presenter')) {
  /**
   * Instantiate and return a presenter wrapping the passed object
   *
   * @param  Deefour\Presenter\Contracts\Presentable|mixed  $object
   * @param  string  $presenter  [optional]
   * @return Deefour\Presenter\Presenter
   */
  function presenter($object, $presenter = null) {
    $collection = null;

    if ($object instanceof Collection) {
     $collection = $object->all();
    } elseif (is_array($object)) {
      $collection = $object;
    }

    if ( ! is_null($collection)) {
      $objects = [];

      foreach ($collection as $item) {
        $objects[] = presenter($item, $presenter);
      }

      if ($object instanceof Collection) {
        return new Collection($objects);
      }

      return $objects;
    }

    if (function_exists('app') and app() instanceof Container) {
      $factory = app('presenter');
    } else {
      $factory = new Factory;
    }

    return $factory->makeOrFail($object, $presenter);
  }
}
