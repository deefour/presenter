<?php namespace spec\Deefour\Presenter\Stubs\Presenters;

use Deefour\Presenter\Stubs\Category;
use Deefour\Presenter\Stubs\Presenters\CategoryPresenter;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategoryPresenterSpec extends ObjectBehavior {

  function let() {
    $this->beConstructedWith(new Category);
  }

  function it_is_initializable() {
    $this->shouldHaveType(CategoryPresenter::class);
  }

  function it_should_not_retrieve_protected_properties() {
    $this->unavailable->shouldReturn(null);
  }

  function it_should_retrieve_public_properties() {
    $this->available->shouldReturn(true);
  }

}
