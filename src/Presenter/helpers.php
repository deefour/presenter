<?php

use Deefour\Presenter\Factory;
use Deefour\Presenter\Contracts\PresentableContract;

if ( ! function_exists('presenter')) {
  /**
   * Instantiate and return a presenter wrapping the passed object
   *
   * @param  Deefour\Presenter\Contracts\PresentableContract  $object
   * @return Deefour\Presenter\AbstractPresenter
   */
  function presenter(PresentableContract $object) {
    return (new Factory)->makeOrFail($object);
  }
}
