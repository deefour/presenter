<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Exception\NotDefinedException;
use ReflectionClass;

class Resolver
{
    public $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function presenter()
    {
        $klass = $this->find();

        return class_exists($klass) ? $klass : null;
    }

    public function presenterOrFail()
    {
        if (is_null($this->object)) {
            throw new NotDefinedException('Unable to find presenter of null');
        }

        if ($presenter = $this->presenter()) {
            return $presenter;
        }

        throw new NotDefinedException('Unable to find presenter for ' . get_class($this->object));
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
    public function find()
    {
        if (is_null($this->object)) {
            return null;
        }

        if (is_object($this->object)) {
            $reflection   = new ReflectionClass($this->object);
            $lookupMethod = 'presenterClass';

            if ($reflection->hasMethod($lookupMethod)) {
                return call_user_func(join('::', [ $reflection->name, $lookupMethod ]));
            }
        }

        if ($base = $this->findClassName($this->object)) {
            return "{$base}Presenter";
        }

        return null;
    }

    /**
     * Attempt to use reflecton to determine the FQN of a class related to $subject
     * that should be treated as the soure of the policy or scope name. The
     * reflection checks for a static modelClass() method on the $subject. 'Policy'
     * or 'Scope' will be appended to the returned FQN.
     *
     * @param  mixed  $subject
     * @return string
     */
    protected function findClassName($subject)
    {
        if ( ! is_object($subject) && ! class_exists($subject)) {
            return null;
        }

        $reflection = new ReflectionClass($subject);

        if ($reflection->hasMethod('modelClass')) {
            return call_user_func($reflection->name . '::modelClass');
        }

        if (is_string($subject)) {
            return $subject;
        }

        return get_class($subject);
    }
}
