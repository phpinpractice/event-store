<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\EntityCanBeReconstituted;

class ArticleTreeProjector
{
    use EntityCanBeReconstituted;

    private $articles = [];

    public function articles()
    {
        return $this->articles;
    }

    private function whenPostedArticle(PostedArticle $event)
    {
        $article        = new ReadModel\Article();
        $article->id    = $event->articleId();
        $article->title = $event->title();
        $article->body  = $event->body();

        $this->articles[(string)$event->articleId()] = $article;
    }

    private function whenArticleTitleChanged(ArticleTitleChanged $event)
    {
        $this->articles[(string)$event->articleId()]->title = $event->title();
    }

    private function whenCommented(Commented $event)
    {
        $comment = new ReadModel\Comment();
        $comment->id     = $event->commentId();
        $comment->body   = $event->body();
        $comment->author = $event->author();
        $this->articles[(string)$event->articleId()]->comments[(string)$event->commentId()] = $comment;
    }
}
