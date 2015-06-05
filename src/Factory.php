<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Presenter\Exceptions\NotDefinedException;
use Deefour\Presenter\Exceptions\NotPresentableException;
use ReflectionClass;

class Factory
{
    /**
     * Derives a presenter class name fo rthe object the finder was passed when
     * instantiated. There is no check made here to see if the class actually
     * exists.
     *
     * @param Presentable $object
     * @param string      $presenter [optional]
     *
     * @return string
     */
    public function make(Presentable $object, $presenter = null)
    {
        $presenter = $this->resolve($object, $presenter);

        if (!$this->isValidPresenter($presenter)) {
            return;
        }

        return new $presenter($object);
    }

    /**
     * Derives a presenter class name for the object the finder was passed when
     * instantiated. If the presenter does not exists an exception is thrown.
     *
     * @param Presentable $object
     * @param string      $presenter [optional]
     *
     * @return string
     *
     * @throws NotDefinedException
     */
    public function makeOrFail(Presentable $object, $presenter = null)
    {
        $presenter = $this->resolve($object, $presenter);

        if (!$this->isValidPresenter($presenter)) {
            throw new NotDefinedException(sprintf(
                'Unable to find presenter class for [%s]',
                get_class($object)
            ));
        }

        return $this->make($object, $presenter);
    }

    /**
     * Derives the class name for the object the finder was passed when
     * instantiated.
     *
     * @throws NotPresentableException
     *
     * @param string|Presentable $object
     * @param string             $presenter [optional]
     *
     * @return string
     */
    public function resolve($object, $presenter = null)
    {
        if (!($object instanceof Presentable)) {
            throw new NotPresentableException(sprintf(
                'The `%s` object does not implement `%s`; presentation cannot'.
                ' be performed',
                get_class($object),
                Presentable::class
            ));
        }

        if (!is_null($presenter)) {
            return $presenter;
        }

        $namespace = $object->presenterNamespace();
        $klass     = $object->presenterClass();

        if (class_exists($klass)) {
            return $klass;
        }

        $shortName = (new ReflectionClass($object))->getShortName();

        return implode('\\', [$namespace, $shortName.'Presenter']);
    }

    /**
     * Ensures the FQCN passed maps to a valid, existing presenter class.
     *
     * @param string|object $className
     *
     * @return bool
     */
    protected function isValidPresenter($className)
    {
        if (is_object($className)) {
            $className = get_class($className);
        }

        return class_exists($className) && is_a($className, Presenter::class, true);
    }
}
