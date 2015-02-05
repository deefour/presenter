<?php namespace Deefour\Presenter;

use Deefour\Presenter\Factory;

trait Presentable {

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
  public function presenter() {
    return (new Factory)->makeOrFail($this);
  }

}