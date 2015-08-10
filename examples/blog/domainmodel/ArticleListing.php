<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\EntityCanBeReconstituted;

class ArticleListing
{
    use EntityCanBeReconstituted;

    protected $articles = [];

    protected function whenPostedArticle(PostedArticle $event)
    {
        $this->articles[(string)$event->articleId()] = [
            'title' => $event->title(),
            'commentCount' => 0
        ];
    }

    protected function whenCommented(Commented $event)
    {
        $this->articles[(string)$event->articleId()]['commentCount']++;
    }

    public function getProjection()
    {
        return $this->articles;
    }
}
