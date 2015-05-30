<?php namespace Deefour\Presenter\Facades;

/**
 * @see \Deefour\Presenter\Factory
 */
class Presenter extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'presenter'; }

}
