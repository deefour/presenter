<?php

namespace Deefour\Presenter;

use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use InvalidArgumentException;
use IteratorAggregate;
use ReflectionProperty;

abstract class Presenter
{
    /**
     * The raw model object being decorated by the presenter.
     *
     * @protected
     *
     * @var Presentable
     */
    protected $_model;

    protected $_resolver;

    /**
     * A static mapping of snake-to-camel cased conversions.
     *
     * @var array
     */
    protected static $_caseMappingCache = [];

    /**
     * Constructor.
     *
     * @param Presentable $model
     */
    public function __construct($model)
    {
        $this->_model    = $model;
        $this->_resolver = new Resolver;
    }

    public function model()
    {
        return $this->_model;
    }

    /**
     * Magic getter. Provides property access to properties on the presenter,
     * properties on the underlying model, and methods on each by converting the
     * snake-cased property name into camel case.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($property === 'model') {
            throw new InvalidArgumentException('Use the [model()] method to request the underlying model object');
        }

        return $this->_derive($property);
    }

    /**
     * Magic caller (ie. missing method handler). Provides transparent access to
     * methods on the model.
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->_derive($method, $args);
    }

    /**
     * Magic isset.
     *
     * @param  string $property
     * @return bool
     */
    public function __isset($property)
    {
        $method = $this->_deriveMethodName($property);

        if (method_exists($this, $method) || method_exists($this->_model, $method)) {
            return true;
        } elseif (property_exists($this, $property)) {
            return isset($this->$property);
        } else {
            return isset($this->_model->$property);
        }
    }

    /**
     * Snake-to-camel-case string conversion.
     *
     * @link https://github.com/illuminate/support/blob/master/Str.php
     *
     * @param  string $property
     * @return string
     */
    protected function _deriveMethodName($property)
    {
        if (in_array($property, static::$_caseMappingCache)) {
            return static::$_caseMappingCache[ $property ];
        }

        $converted = ucwords(str_replace(['-', '_'], ' ', $property));
        $converted = lcfirst(str_replace(' ', '', $converted));

        static::$_caseMappingCache[ $property ] = $converted;

        return $converted;
    }

    /**
     * Attempts to derive the property/method value on the presenter and model in
     * the following order:.
     *
     *   1. The property on the presenter
     *   2. The method on the presenter
     *   3. The property on the model
     *   4. The method on the model
     *
     * isset is used in favor of property_exists to play nicely with __isset
     * implementations on the presentable object model.
     *
     * Property access is denied for protected/private properties.
     *
     * This will fail loudly if the property/method could not be derived.
     *
     * @param  string $property
     * @param  array  $args
     * @return mixed
     */
    protected function _derive($property, array $args = [])
    {
        if (property_exists($this, $property) && (new ReflectionProperty($this, $property))->isPublic()) {
            return $this->$property;
        }

        $method = $this->_deriveMethodName($property);

        if (method_exists($this, $method)) {
            return $this->decorate(call_user_func_array([$this, $method], $args));
        }

        if (method_exists($this->_model, $method)) {
            return $this->decorate(call_user_func_array([$this->_model, $method], $args));
        }

        if (isset($this->_model->$property)) {
            return $this->decorate($this->_model->$property);
        }

        return;
    }

    /**
     * When a property or method is requested through the presenter, an attempt
     * is
     * made to wrap the return value in it's own presenter.
     *
     * If the attempt fails due to no presenter existing for the presentable
     * object, or if the object simply isn't presentable, the raw value will
     * instead be returned.
     *
     * Support is available for IteratorAggregate instances, looping through the
     * collection and attempting to wrap each object in it's own presenter.
     *
     * @throws Exception
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function decorate($value)
    {
        // Laravel relation
        if (is_a($value, Relation::class)) {
            return $this->decorate($value->getResults());
        }

        if ($value instanceof IteratorAggregate) {
            $collection = get_class($value);
            $items      = [];

            foreach ($value as $item) {
                $items[] = $this->decorate($item);
            }

            return new $collection($items);
        }

        $presenter = $this->resolver()->presenter($value);

        if (is_null($presenter)) {
            return $value;
        }

        return new $presenter($value);
    }

    protected function resolver()
    {
        return $this->_resolver;
    }
}
