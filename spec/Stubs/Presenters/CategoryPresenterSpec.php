<?php

namespace spec\Deefour\Presenter\Stubs\Presenters;

use Deefour\Presenter\Stubs\Category;
use Deefour\Presenter\Stubs\Presenters\CategoryPresenter;
use PhpSpec\ObjectBehavior;

class CategoryPresenterSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Category());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CategoryPresenter::class);
    }

    public function it_should_not_retrieve_protected_properties()
    {
        $this->unavailable->shouldReturn(null);
    }

    public function it_should_retrieve_public_properties()
    {
        $this->available->shouldReturn(true);
    }
}
