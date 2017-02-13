<?php

namespace Deefour\Presenter\Stubs;

use Deefour\Presenter\Presenter;

class ArticlePresenter extends Presenter
{
    public function title()
    {
        return ucwords($this->_model->title);
    }
}
