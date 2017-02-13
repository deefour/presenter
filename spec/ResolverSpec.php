<?php

namespace spec\Deefour\Presenter;

use Deefour\Presenter\Exception\NotDefinedException;
use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Podcast;
use Deefour\Presenter\Stubs\Post;
use Deefour\Presenter\Stubs\ArticlePresenter;
use Deefour\Presenter\Stubs\PeriodicalPresenter;
use Deefour\Presenter\Stubs\Tag;
use Deefour\Producer\Exceptions\NotProducibleException;
use PhpSpec\ObjectBehavior;

class ResolverSpec extends ObjectBehavior
{
    public function it_should_resolve_presenter_fqcn()
    {
        $this->beConstructedWith(new Article);
        $this->find()->shouldReturn(ArticlePresenter::class);
        $this->presenter()->shouldReturn(ArticlePresenter::class);
        $this->presenterOrFail()->shouldReturn(ArticlePresenter::class);
    }

    public function it_should_resolve_through_model_class()
    {
        $this->beConstructedWith(new Post);
        $this->find()->shouldReturn(ArticlePresenter::class);
        $this->presenter()->shouldReturn(ArticlePresenter::class);
        $this->presenterOrFail()->shouldReturn(ArticlePresenter::class);
    }

    public function it_should_resolve_custom_presenter()
    {
        $this->beConstructedWith(new Podcast);
        $this->find()->shouldReturn(PeriodicalPresenter::class);
        $this->presenter()->shouldReturn(PeriodicalPresenter::class);
        $this->presenterOrFail()->shouldReturn(PeriodicalPresenter::class);
    }

    public function it_returns_non_existant_classes_for_later_verification()
    {
        $this->beConstructedWith(new Tag);
        $this->find()->shouldReturn('Deefour\Presenter\Stubs\TagPresenter');
    }

    public function it_can_throw_not_defined_exception_when_asked()
    {
        $this->beConstructedWith(new Tag);
        $this->shouldThrow(NotDefinedException::class)->during('presenterOrFail');
    }
}
