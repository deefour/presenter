<?php

namespace spec\Deefour\Presenter;


use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Deefour\Presenter\Stubs\Tag;
use Deefour\Presenter\Stubs\Podcast;
use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Presenters\ArticlePresenter;
use Deefour\Producer\Exceptions\NotProducibleException;

class FactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Deefour\Presenter\Factory');
    }

    public function it_should_resolve_presenter_fqcn()
    {
        $this->resolve(new Article)->shouldReturn(ArticlePresenter::class);
    }

    public function it_should_make_presenter_classes()
    {
        $this->make(new Article)->shouldReturnAnInstanceOf(ArticlePresenter::class);
    }

    public function it_should_return_null_if_make_fails()
    {
        $this->make(new Tag)->shouldBeNull();
    }

    public function it_should_throw_exception_on_make_or_fail()
    {
      $this->shouldThrow(NotProducibleException::class)->duringMakeOrFail(new Podcast);
    }
}
