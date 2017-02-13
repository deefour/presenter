<?php

namespace spec\Deefour\Presenter\Stubs;

use Deefour\Presenter\Stubs\Article;
use Deefour\Presenter\Stubs\Author;
use Deefour\Presenter\Stubs\Category;
use Deefour\Presenter\Stubs\ArticlePresenter;
use Deefour\Presenter\Stubs\CategoryPresenter;
use Deefour\Presenter\Stubs\EventPresenter;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;

class ArticlePresenterSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Article);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ArticlePresenter::class);
    }

    public function it_should_allow_property_access_to_underlying_model()
    {
        $this->_model->shouldBeAnInstanceOf(Article::class);
    }

    public function it_should_allow_method_access_to_underlying_model()
    {
        $this->_model()->shouldBeAnInstanceOf(Article::class);
    }

    public function it_should_map_snake_case_property_to_camel_case_model_method()
    {
        $this->is_active->shouldBe(true);

        $this->_model->title->shouldBe('sample article');
        $this->title->shouldBe('Sample Article');
    }

    public function it_should_not_respond_to_model_access_without_underscore_prefix()
    {
        $this->model->shouldBe(null);
    }

    public function it_should_pass_unknown_method_through_to_model()
    {
        $this->zipCode()->shouldBe('06483');
    }

    public function it_should_pass_unknown_properties_through_to_model()
    {
        $this->street_address->shouldBe('1 Maple Ave');
    }

    public function it_should_decorate_presentable_return_values()
    {
        $this->category->shouldBeAnInstanceOf(CategoryPresenter::class);
        $this->related_events->shouldBeAnInstanceOf(Collection::class);
        $this->related_events->first()->shouldBeAnInstanceOf(EventPresenter::class);
    }

    public function it_should_not_fail_decoration_attempts_of_associations()
    {
        $this->author->shouldBeAnInstanceOf(Author::class);
    }
}
