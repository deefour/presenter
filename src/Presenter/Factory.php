<?php namespace Deefour\Presenter;

use Deefour\Presenter\Contracts\PresentableContract;
use Deefour\Presenter\Exceptions\NotPresentableException;
use Deefour\Presenter\Exceptions\NotDefinedException;
use ReflectionClass;

class Factory {

  /**
   * Derives a presenter class name fo rthe object the finder was passed when
   * instantiated. There is no check made here to see if the class actually exists.
   *
   * @return string
   */
  public function make($object) {
    $presenter = $this->resolve($object);

    if ( ! class_exists($presenter)) {
      return null;
    }

    return new $presenter($object);
  }

  /**
   * Derives a presenter class name fo rthe object the finder was passed when
   * instantiated. If the presenter does not exists an exception is thrown.
   *
   * @throws Deefour\Presenter\Exceptions\NotDefinedException
   */
  public function makeOrFail($object) {
    $presenter = $this->resolve($object);

    if ( ! class_exists($presenter)) {
      throw new NotDefinedException(sprintf('Unable to find presenter class for `%s`', get_class($object)));
    }

    return $this->make($object);
  }

  /**
   * Derives the class name for the object the finder was passed when instantiated.
   *
   * @throws Deefour\Presenter\Exceptions\NotAuthorizableException
   * @return string
   */
  public function resolve($object) {
    if ( ! ($object instanceof PresentableContract)) {
      throw new NotPresentableException(sprintf('The `%s` object does not implement `%s`; presentation cannot be performed', get_class($object), PresentableContract::class));
    }

    $namespace = $object->presenterNamespace();
    $klass     = $object->presenterClass();

    if (class_exists($klass)) {
      return $klass;
    }

    $shortName = (new ReflectionClass($object))->getShortName();

    return join('\\', [ $namespace, $shortName . 'Presenter' ]);
  }

}
