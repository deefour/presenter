<?php namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\ResolvesPresenters;
use Illuminate\Support\Fluent;

abstract class Model extends Fluent implements Presentable {

  use ResolvesPresenters;

  /**
   * @inheritdoc
   */
  public function presenterNamespace() {
    return 'Deefour\\Presenter\\Stubs\\Presenters';
  }

}
