<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Exceptions\NotPresentableException;

trait ProducesPresenters
{
    /**
     * {@inheritdoc}
     */
    public function presenter($presenter = null)
    {
        if (is_null($presenter) || is_a($presenter, Presenter::class, true)) {
            return $this->produce($presenter ?: 'presenter');
        }

        throw new NotPresentableException($this);
    }
}
