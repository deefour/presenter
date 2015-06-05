<?php

use Deefour\Presenter\Factory;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;

if (!function_exists('present')) {
    /**
     * Instantiate and return a presenter wrapping the passed object.
     *
     * @param Presentable|mixed $object
     * @param string            $presenter [optional]
     *
     * @return Deefour\Presenter\Presenter
     */
    function present($object, $presenter = null)
    {
        $collection = null;

        if ($object instanceof Collection) {
            $collection = $object->all();
        } elseif (is_array($object)) {
            $collection = $object;
        }

        if (!is_null($collection)) {
            $objects = [];

            foreach ($collection as $item) {
                $objects[] = present($item, $presenter);
            }

            if ($object instanceof Collection) {
                return new Collection($objects);
            }

            return $objects;
        }

        if (function_exists('app') && app() instanceof Container) {
            $factory = app('presenter');
        } else {
            $factory = new Factory();
        }

        return $factory->makeOrFail($object, $presenter);
    }
}
