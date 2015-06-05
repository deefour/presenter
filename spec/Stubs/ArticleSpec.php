<?php

namespace spec\Deefour\Presenter\Stubs;

use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Presenters\ArticlePresenter;
use Deefour\Presenter\Stubs\Presenters\FeaturedArticlePresenter;
use PhpSpec\ObjectBehavior;

class ArticleSpec extends ObjectBehavior
{
  public function it_is_initializable()
  {
      $this->shouldHaveType(Article::class);
  }

    public function it_should_resolve_presenter()
    {
        $this->presenter()->shouldBeAnInstanceOf(ArticlePresenter::class);
    }

    public function it_should_resolve_custom_presenter()
    {
        $this->presenter(FeaturedArticlePresenter::class)->shouldBeAnInstanceOf(FeaturedArticlePresenter::class);
    }
}
