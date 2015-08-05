<?php

namespace Deefour\Presenter\Contracts;

use Deefour\Presenter\Presenter;
use Deefour\Producer\Contracts\Producer;

interface Presentable extends Producer
{
    /**
     * Wrap this object in a newly instantiated presenter.
     *
     * @param string $presenter [optional]
     *
     * @return Presenter
     */
    public function presenter($presenter = null);
}
