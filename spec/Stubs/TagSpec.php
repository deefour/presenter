<?php

namespace spec\Deefour\Presenter\Stubs;

use Deefour\Presenter\Exceptions\NotPresentableException;
use Deefour\Presenter\Stubs\Tag;
use PhpSpec\ObjectBehavior;

class TagSpec extends ObjectBehavior
{
  public function it_is_initializable()
  {
      $this->shouldHaveType(Tag::class);
  }

    public function it_throws_exception_for_unresolvable_presenter()
    {
        $this->shouldThrow(NotPresentableException::class)->during('presenter');
    }
}
