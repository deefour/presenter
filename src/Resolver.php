<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Exception\NotDefinedException;
use ReflectionClass;

class Resolver
{
    protected $resolver;

    public function __construct()
    {
        // The default resolver
        $this->resolveWith(function ($base) {
            return "{$base}Presenter";
        });
    }

    public function presenter($object)
    {
        $klass = $this->find($object);

        return class_exists($klass) ? $klass : null;
    }

    public function presenterOrFail($object)
    {
        if (is_null($object)) {
            throw new NotDefinedException('Unable to find presenter of null');
        }

        if ($presenter = $this->presenter($object)) {
            return $presenter;
        }

        throw new NotDefinedException('Unable to find presenter for ' . get_class($object));
    }

    public function resolveWith(callable $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Resolve the class name using the $suffix as the type of class to resolve
     * for the $object on this class instance.
     *
     * If the $object is a class instance, reflection is used to statically call
     * a policyClass() or scopeClass() method on the $object to get the class name
     * for the policy or scope respectively.
     *
     * If the $object is a string, further reflection is used to determine the FQN
     * of a related class instance to treat as the source of the policy or scope
     * name.
     *
     * @param  string     $suffix
     * @return mixed|null
     */
    public function find($object)
    {
        if (is_null($object)) {
            return null;
        }

        $resolver = $this->resolver;

        return $resolver($this->findClassName($object));
    }

    /**
     * Attempt to use reflecton to determine the FQN of a class related to $object
     * that should be treated as the soure of the policy or scope name. The
     * reflection checks for a static modelClass() method on the $object. 'Policy'
     * or 'Scope' will be appended to the returned FQN.
     *
     * @param  mixed  $object
     * @return string
     */
    protected function findClassName($object)
    {
        if ( ! is_object($object) && ! class_exists($object)) {
            return null;
        }

        $reflection = new ReflectionClass($object);

        if ($reflection->hasMethod('modelClass')) {
            return call_user_func($reflection->name . '::modelClass');
        }

        if (is_string($object)) {
            return $object;
        }

        return get_class($object);
    }
}
