<?php namespace Deefour\Presenter;

trait ResolvesPresenters {

  /**
   * @inheritdoc
   */
  public function presenterNamespace() {
    return '';
  }

  /**
   * @inheritdoc
   */
  public function presenterClass() {
    return static::class . 'Presenter';
  }

  /**
   * @inheritdoc
   */
  public function presenter($presenter = null) {
    $factory = new Factory;

    // Ensure the class using this trait is implementing the Presentable contract
    // and throw a graceful error otherwise.
    $factory->resolve($this);

    return $factory->makeOrFail($this, $presenter);
  }

}
