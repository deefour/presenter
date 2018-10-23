<?php

namespace Deefour\Presenter;

/**
 * Given an FQN, returns a potential FQN for it's related presenter.
 */
interface ResolvesPresenterFqns {
    public function resolve($subject): ?string;
}
