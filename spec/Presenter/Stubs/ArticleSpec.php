<?php namespace spec\Deefour\Presenter\Stubs;

use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Presenters\ArticlePresenter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleSpec extends ObjectBehavior {

  function it_is_initializable() {
    $this->shouldHaveType(Article::class);
  }

  function it_should_resolve_presenter() {
    $this->presenter()->shouldBeAnInstanceOf(ArticlePresenter::class);
  }

}
