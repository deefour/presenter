<?php namespace Deefour\Presenter\Contracts;

interface PresentableContract {

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
   * @return Deefour\Presenter\AbstractPresenter
   */
  public function presenter();

}
