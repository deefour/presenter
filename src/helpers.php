<?php

use Deefour\Presenter\Presenter;
use Deefour\Presenter\Exceptions\NotPresentableException;

if (!function_exists('present')) {
    /**
     * Instantiate and return a presenter wrapping the passed object.
     *
     * @param Presentable|mixed $object
     * @param string            $presenter [optional]
     *
     * @return Deefour\Presenter\Presenter
     */
    function present($object, $with = 'presenter')
    {
        if ($with !== 'presenter' && !is_a($with, Presenter::class, true)) {
          throw new NotPresentableException($object, $with);
        }

        return produce($object, $with);
    }
}
