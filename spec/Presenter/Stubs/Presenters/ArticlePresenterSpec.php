<?php namespace spec\Deefour\Presenter\Stubs\Presenters;

use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Author;
use Deefour\Presenter\Stubs\Category;
use Deefour\Presenter\Stubs\Presenters\ArticlePresenter;
use Deefour\Presenter\Stubs\Presenters\CategoryPresenter;
use Deefour\Presenter\Stubs\Presenters\EventPresenter;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticlePresenterSpec extends ObjectBehavior {

  function let() {
    $this->beConstructedWith(new Article);
  }

  function it_is_initializable() {
    $this->shouldHaveType(ArticlePresenter::class);
  }

  function it_should_allow_property_access_to_underlying_model() {
    $this->model->shouldBeAnInstanceOf(Article::class);
    $this->model()->shouldBeAnInstanceOf(Article::class);
  }

  function it_should_map_snake_case_property_to_camel_case_model_method() {
    $this->is_active->shouldBe(true);

    $this->model->title->shouldBe('sample article');
    $this->title->shouldBe('Sample Article');
  }

  function it_should_pass_unknown_method_through_to_model() {
    $this->zipCode()->shouldBe('06483');
  }

  function it_should_pass_unknown_properties_through_to_model() {
    $this->street_address->shouldBe('1 Maple Ave');
  }

  function it_should_decorate_presentable_return_values() {
    $this->category->shouldBeAnInstanceOf(CategoryPresenter::class);
    $this->related_events->shouldBeAnInstanceOf(Collection::class);
    $this->related_events->first()->shouldBeAnInstanceOf(EventPresenter::class);
  }

  function it_should_not_fail_decoration_attempts_of_associations() {
    $this->author->shouldBeAnInstanceOf(Author::class);
  }

}
