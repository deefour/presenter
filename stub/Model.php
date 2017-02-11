<?php

namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\ProducesPresenters;
use Deefour\Producer\ProducesClasses;
use Deefour\Producer\ResolvesProducibles;
use Illuminate\Support\Fluent;

abstract class Model extends Fluent implements Presentable
{
    use ProducesPresenters, ResolvesProducibles, ProducesClasses;
}
