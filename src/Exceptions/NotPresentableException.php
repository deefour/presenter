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
    protected $presentable;

    /**
     * The presenter FQCN.
     *
     * @var string
     */
    protected $presenter;

    /**
     * Constructor.
     *
     * @param Presentable $presentable
     * @param string $presenter
     */
    public function __construct(Presentable $presentable, $presenter) {
        $this->presentable = $presentable;
        $this->presenter   = $presenter;

        parent::__construct($this->message());
    }

    /**
     * Format a message for the exception.
     *
     * @return string
     */
    protected function message() {
        return sprintf(
            'The [%s] object does not implement [%s]. It cannot be used to ' .
            'decorate [%s]',
            $this->presenter,
            Presentable::class,
            get_class($this->presentable)
        );
    }
}
