<?php

namespace Deefour\Presenter\Exception;

/**
 * Thrown when the resolver fails to locate a presenter class for a passed
 * $record.
 *
 * @see Resolver::presenterOrFail()
 */
class NotDefinedException extends Exception
{
    //
}
