<?php namespace Deefour\Presenter\Contracts;

interface Presentable {

  /**
   * Custom namespace for presenter class resolution
   *
   * @return string
   */
  public function presenterNamespace();

  /**
   * Custom short name for the presenter class associated with this object
   *
   * @return string
   */
  public function presenterClass();

  /**
   * Wrap this object in a newly instantiated presenter
   *
   * @param  string|object  $presenter  [optional]
   * @return Deefour\Presenter\Presenter
   */
  public function presenter($presenter = null);

}
