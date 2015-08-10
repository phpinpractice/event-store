<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\AggregateRootIsEventSourced;
use Rhumsaa\Uuid\Uuid;

class Article
{
    use AggregateRootIsEventSourced;

    private $id;

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

    public function comment($author, $body)
    {
        $this->when(new Commented($this->id, Uuid::uuid4(), $body, $author));
    }

    private function whenPostedArticle(PostedArticle $postedArticle)
    {
        $this->id = $postedArticle->articleId();
    }
}
