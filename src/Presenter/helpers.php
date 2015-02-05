<?php

use Deefour\Presenter\Factory;
use Deefour\Presenter\Contracts\Presentable as PresentableContract;
use Illuminate\Container\Container;

if ( ! function_exists('presenter')) {
  /**
   * Instantiate and return a presenter wrapping the passed object
   *
   * @param  Deefour\Presenter\Contracts\Presentable  $object
   * @return Deefour\Presenter\Presenter
   */
  function presenter(PresentableContract $object) {
    if (function_exists('app') and app() instanceof Container) {
      $factory = app('presenter');
    } else {
      $factory = new Factory;
    }

    return $factory->makeOrFail($object);
  }
}
