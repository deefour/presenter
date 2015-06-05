<?php

namespace spec\Deefour\Presenter\Stubs;

use Deefour\Presenter\Exceptions\NotDefinedException;
use Deefour\Presenter\Stubs\Author;
use PhpSpec\ObjectBehavior;

class AuthorSpec extends ObjectBehavior
{
  public function it_is_initializable()
  {
      $this->shouldHaveType(Author::class);
  }

    public function it_throws_exception_for_unresolvable_presenter()
    {
        $this->shouldThrow(NotDefinedException::class)->during('presenter');
    }
}
