<?php

namespace PhpInPractice\EventStore\Example\Blog\ReadModel;

final class Article
{
    public $id;
    public $title;
    public $body;
    public $comments = [];
}
