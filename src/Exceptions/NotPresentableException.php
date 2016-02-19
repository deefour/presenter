<?php

namespace Deefour\Presenter\Exceptions;

use Deefour\Producer\Exceptions\NotProducibleException;
use Deefour\Presenter\Contracts\Presentable;

class NotPresentableException extends NotProducibleException
{
    /**
     * The object to wrap in a presenter.
     *
     * @var Presentable
     */
    public $presentable;

    /**
     * Constructor.
     *
     * @param Presentable $presentable
     * @param string $presenter
     */
    public function __construct(Presentable $presentable) 
    {
        $this->presentable = $presentable;
    }

    /**
     * Format a message for the exception.
     *
     * @return string
     */
    protected function getMessage() 
    {
        return sprintf(
            'The [%s] object does not implement [%s].',
            get_class($this->presentable),
            Presentable::class,
        );
    }
}
