<?php

namespace Deefour\Presenter\Stubs\Presenters;

use Deefour\Presenter\Presenter;

class FeaturedArticlePresenter extends Presenter
{
  public function title()
  {
      return strtolower($this->_model->title);
  }
}
