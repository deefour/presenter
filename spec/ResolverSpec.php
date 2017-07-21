<?php

namespace spec\Deefour\Presenter;

use Deefour\Presenter\Exception\NotDefinedException;
use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\ArticlePresenter;
use Deefour\Presenter\Stubs\Post;
use Deefour\Presenter\Stubs\Tag;
use PhpSpec\ObjectBehavior;

class ResolverSpec extends ObjectBehavior
{
    public function it_should_resolve_presenter_fqcn()
    {
        $this->find(new Article)->shouldReturn(ArticlePresenter::class);
        $this->presenter(new Article)->shouldReturn(ArticlePresenter::class);
        $this->presenterOrFail(new Article)->shouldReturn(ArticlePresenter::class);
    }

    public function it_should_resolve_through_model_class()
    {
        $this->find(new Post)->shouldReturn(ArticlePresenter::class);
        $this->presenter(new Post)->shouldReturn(ArticlePresenter::class);
        $this->presenterOrFail(new Post)->shouldReturn(ArticlePresenter::class);
    }

    public function it_returns_non_existant_classes_for_later_verification()
    {
        $this->find(new Tag)->shouldReturn('Deefour\Presenter\Stubs\TagPresenter');
    }

    public function it_can_throw_not_defined_exception_when_asked()
    {
        $this->shouldThrow(NotDefinedException::class)->during('presenterOrFail', [new Tag]);
    }

    public function it_should_use_custom_resolver()
    {
        $this->resolveWith(function () {
            return 'testing';
        });

        $this->find(new Article)->shouldReturn('testing');
    }
}
