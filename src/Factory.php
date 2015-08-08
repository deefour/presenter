<?php

namespace Deefour\Presenter;

use Deefour\Presenter\Contracts\Presentable;
use Deefour\Producer\Factory as ProductionFactory;
use Deefour\Producer\Contracts\ProductionFactory as ProductionFactoryContract;
use Deefour\Producer\Contracts\Producer;

class Factory
{
    /**
     * The production factory.
     *
     * @var ProductionFactory
     */
    protected $productionFactory;

    /**
     * Constructor.
     *
     * @param ProductionFactory $productionFactory [optional]
     */
    public function __construct(ProductionFactoryContract $productionFactory = null)
    {
        if (is_null($productionFactory)) {
            $productionFactory = new ProductionFactory();
        }

        $this->productionFactory = $productionFactory;
    }
    /**
     * Generate an FQCN for the presenter based on the name of the presentable
     * object passed.
     *
     * @param Producer $object
     * @return string
     */
    public function resolve(Presentable $object)
    {
        return $this->productionFactory->resolve($object, 'presenter');
    }

    /**
     * Derives a presenter for the passed object. Null is returned
     * if the object fails to be resolved.
     *
     * @param Producer $object
     * @return Presenter|null
     */
    public function make(Producer $object)
    {
        return $this->productionFactory->make($object, 'presenter');
    }

    /**
       * Derives a presenter for the passed object. If the presenter does not
       * exist an exception is thrown.
       *
       * @throws NotProducibleException
       * @param Producer $object
       * @return Presenter
       */
    public function makeOrFail(Presentable $object)
    {
        return $this->productionFactory->makeOrFail($object, 'presenter');
    }
}
