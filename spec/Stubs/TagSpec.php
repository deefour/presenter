<?php

namespace spec\Deefour\Presenter\Stubs;

use Deefour\Producer\Exceptions\NotProducibleException;
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
        $this->shouldThrow(NotProducibleException::class)->during('presenter');
    }
}
