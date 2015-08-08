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
    function it_is_initializable()
    {
        $this->shouldHaveType('Deefour\Presenter\Factory');
    }

    function it_should_resolve_presenter_fqcn()
    {
        $this->resolve(new Article)->shouldReturn(ArticlePresenter::class);
    }

    function it_should_make_presenter_classes()
    {
        $this->make(new Article)->shouldReturnAnInstanceOf(ArticlePresenter::class);
    }

    function it_should_return_null_if_make_fails()
    {
        $this->make(new Tag)->shouldBeNull();
    }

    function it_should_throw_exception_on_make_or_fail()
    {
      $this->shouldThrow(NotProducibleException::class)->duringMakeOrFail(new Podcast);
    }
}
