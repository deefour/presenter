<?php namespace Deefour\Presenter;

use BadMethodCallException;
use Deefour\Presenter\Exceptions\UnknownPropertyException;
use Deefour\Presenter\Exceptions\NotDefinedException;
use Deefour\Presenter\Exceptions\NotPresentableException;
use Deefour\Presenter\Contracts\Presentable;
use Exception;
use IteratorAggregate;

abstract class Presenter {

  /**
   * The raw model object being decorated by the presenter
   *
   * @protected
   * @var Presentable
   */
  protected $model;



  public function __construct(Presentable $model) {
    $this->model   = $model;
    $this->factory = new Factory;
  }

  /**
   * Getter for the object this presenter is decorating
   *
   * @return Presentable
   */
  public function model() {
    return $this->model;
  }

  /**
   * Magic getter. Provides property access to properties on the presenter,
   * properties on the underlying model, and methods on each by converting the
   * snake-cased property name into camel case.
   *
   * @param  string  $property
   * @return mixed
   */
  public function __get($property) {
    if ($property === 'model') {
      return $this->model; // don't decorate the model
    }

    try {
      $value = $this->deriveReturnValue($property);
    } catch (UnknownPropertyException $e) {
      return null;
    }

    return $this->wrapInPresenter($value);
  }

  /**
   * Magic caller (ie. missing method handler). Provides transparent access to
   * methods on the model.
   *
   * @param  string  $method
   * @param  array  $args
   * @return mixed
   */
  public function __call($method, array $args) {
    try {
      $value = $this->deriveReturnValue($method, $args);
    } catch (UnknownPropertyException $e) {
      throw new BadMethodCallException(sprintf('The `%s` method does not exist on the `%s` presenter or underlying `%s` model', $method, static::class, get_class($this->model)));
    }

    return $this->wrapInPresenter($value);
  }

  /**
   * Magic isset.
   *
   * @param  string  $property
   * @return boolean
   */
  public function __isset($property) {
    $method = $this->deriveMethodName($property);

    if (method_exists($this, $method) or method_exists($this->model, $method)) {
      return true;
    } else if (property_exists($this, $property)) {
      return isset($this->$property);
    } else {
      return isset($this->model->$property);
    }
  }



  /**
   * Snake-to-camel-case string conversion.
   *
   * @link https://github.com/illuminate/support/blob/master/Str.php
   * @param  string  $property
   * @return string
   */
  protected function deriveMethodName($property) {
    $property = ucwords(str_replace(array('-', '_'), ' ', $property));

    return lcfirst(str_replace(' ', '', $property));
  }

  /**
   * Attempts to derive the property/method value on the presenter and model in
   * the following order:
   *
   *   1. The property on the presenter
   *   2. The method on the presenter
   *   3. The property on the model
   *   4. The method on the model
   *
   * isset is used in favor of property_exists to play nicely with __isset
   * implementations on the presentable object model.
   *
   * This will fail loudly if the property/method could not be derived.
   *
   * @param  string  $property
   * @param  array  $args  [optional]
   * @return mixed
   */
  protected function deriveReturnValue($property, array $args = []) {
    $method = $this->deriveMethodName($property);

    if (property_exists($this, $property)) {
      return $this->$property;
    }

    if (method_exists($this, $method)) {
      return call_user_func_array([ $this, $method ], $args);
    }


    if (isset($this->model->$property)) {
      return $this->model->$property;
    }

    if (method_exists($this->model, $method)) {
      return call_user_func_array([$this->model, $method], $args);
    }

    throw new UnknownPropertyException(sprintf('No property or method could be derived from the passed `%s` attribute', $property));
  }

  /**
   * When a property or method is requested through the presenter, an attempt is
   * made to wrap the return value in it's own presenter.
   *
   * If the attempt fails due to no presenter existing for the presentable object,
   * or if the object simply isn't presentable, the raw value will instead be returned.
   *
   * Support is available for IteratorAggregate instances, looping through the
   * collection and attempting to wrap each object in it's own presenter.
   *
   * @param  mixed  $value
   * @return mixed
   */
  protected function wrapInPresenter($value) {
    // Laravel relation
    if (is_a($value, 'Illuminate\Database\Eloquent\Relations\Relation')) {
      return $this->wrapInPresenter($value->getResults());
    }

    if ( ! ($value instanceof IteratorAggregate) and ! ($value instanceof Presentable)) {
      return $value;
    }

    if ($value instanceof IteratorAggregate) {
      $collection = get_class($value);
      $items      = [];

      foreach ($value as $item) {
        $items[] = $this->wrapInPresenter($item);
      }

      return new $collection($items);
    }

    try {
      $presenter = $this->factory->makeOrFail($value);

      return new $presenter($value);
    } catch (Exception $e) {
      if ($e instanceof NotDefinedException) {
        return $value;
      }

      throw $e;  // re-throw, something unexpected went wrong
    }
  }

}
