<?php

namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\Stubs\PeriodicalPresenter;

class Podcast extends Model
{
    static public function presenterClass()
    {
        return PeriodicalPresenter::class;
    }
}
