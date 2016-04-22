<?php

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\Presenter;

if ( ! function_exists('present')) {
    /**
     * Instantiate and return a presenter wrapping the passed object.
     *
     * @param Presentable|mixed $object
     * @param string            $presenter [optional]
     *
     * @return Presenter
     */
    function present($object, $with = 'presenter')
    {
        if ($object instanceof Presenter && ! ($object instanceof Presentable)) {
            return $object;
        }

        return produce($object, $with);
    }
}
