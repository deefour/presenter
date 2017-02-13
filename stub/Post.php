<?php

namespace Deefour\Presenter\Stubs;

class Post extends Article
{
    static public function modelClass()
    {
        return Article::class;
    }
}
