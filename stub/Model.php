<?php namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\Contracts\PresentableContract;
use Deefour\Presenter\Presentable;
use Illuminate\Support\Fluent;

abstract class Model extends Fluent implements PresentableContract {

  use Presentable;

  /**
   * @inheritdoc
   */
  public function presenterNamespace() {
    return 'Deefour\\Presenter\\Stubs\\Presenters';
  }

}
