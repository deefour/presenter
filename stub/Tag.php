<?php

namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\ProducesPresenters;
use Deefour\Producer\Contracts\Producer;
use Deefour\Producer\ProducesClasses;

class Tag implements Producer
{
    use ProducesPresenters, ProducesClasses;
}
