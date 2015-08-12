<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\EntityCanBeReconstituted;

class ArticleListingProjector
{
    use EntityCanBeReconstituted;

    private $articles = [];

    public function articles()
    {
        return $this->articles;
    }

    private function whenPostedArticle(PostedArticle $event)
    {
        $this->articles[(string)$event->articleId()] = [
            'title' => $event->title(),
            'commentCount' => 0
        ];
    }

    private function whenArticleTitleChanged(ArticleTitleChanged $event)
    {
        $this->articles[(string)$event->articleId()]['title'] = $event->title();
    }

    private function whenCommented(Commented $event)
    {
        $this->articles[(string)$event->articleId()]['commentCount']++;
    }
}
