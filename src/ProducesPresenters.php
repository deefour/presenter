<?php

namespace Deefour\Presenter;

use Deefour\Producer\ProducesClasses;
use Deefour\Presenter\Exceptions\NotPresentableException;

trait ProducesPresenters
{
    use ProducesClasses;

    /**
     * @inheritdoc
     */
    public function presenter($presenter = null)
    {
        if (is_null($presenter) || is_a($presenter, Presenter::class, true)) {
            return $this->produce($presenter ?: 'presenter');
        }

        throw new NotPresentableException($this);
    }
}
