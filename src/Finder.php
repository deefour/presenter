<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Exception\NotDefinedException;
use ReflectionClass;

/**
 * Given a Resolver, finds the FQN of the presenter for an $object.
 */
class Finder
{
    protected $resolver;

    public function __construct(ResolvesPresenterFqns $resolver)
    {
        $this->resolver = $resolver;
    }

    public function find($object)
    {
        $klass = $this->resolver->resolve($object);

        return class_exists($klass) ? $klass : null;
    }

    public function findOrFail($object)
    {
        if (is_null($object)) {
            throw new NotDefinedException('Unable to find presenter of null');
        }

        if ($presenter = $this->resolver->resolve($object)) {
            return $presenter;
        }

        throw new NotDefinedException('Unable to find presenter for ' . get_class($object));
    }
}
