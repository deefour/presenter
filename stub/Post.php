<?php

namespace Deefour\Presenter\Stubs;

class Post extends Article
{
    public static function modelClass()
    {
        return Article::class;
    }
}
