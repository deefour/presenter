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
    public function presenter($with = 'presenter')
    {
        if ($with !== 'presenter' && !is_a($with, Presenter::class, true)) {
            throw new NotPresentableException($this, $with);
        }

        return $this->produce($with);
    }
}
