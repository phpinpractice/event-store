<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\AggregateRootIsEventSourced;

class Article
{
    use AggregateRootIsEventSourced;

    private $id;

    private $body;
    private $title;

    public static function post($title, $body)
    {
        $article = new static();
        $article->when(new PostedArticle(ArticleId::generate(), $title, $body));

        return $article;
    }

    public function id()
    {
        return $this->id;
    }

    private function whenPostedArticle(PostedArticle $postedArticle)
    {
        $this->id    = $postedArticle->articleId();
        $this->title = $postedArticle->title();
        $this->body  = $postedArticle->body();
    }
}
