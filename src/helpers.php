<?php

if ( ! function_exists('present')) {
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
        return produce($object, $with);
    }
}
