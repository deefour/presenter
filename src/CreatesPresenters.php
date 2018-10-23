<?php

namespace Deefour\Presenter;

/**
 * Contract for a factory that will generate a version of $subject that consists
 * of Presenter instances.
 */
interface CreatesPresenters
{
    function make($subject, string $presenter = null);
}
