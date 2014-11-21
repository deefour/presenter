<?php namespace Deefour\Presenter\Stubs\Presenters;

use Deefour\Presenter\AbstractPresenter;

class ArticlePresenter extends AbstractPresenter {

  public function title() {
    return ucwords($this->model->title);
  }

}
