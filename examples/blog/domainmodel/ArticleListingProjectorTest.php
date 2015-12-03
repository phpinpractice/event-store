<?php

namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\CanProject;
use PhpInPractice\EventStore\Projector;

class ArticleListingProjectorTest implements Projector
{
    use CanProject;

    private function whenPostedArticle($data, PostedArticle $event)
    {
        $data[(string)$event->articleId()] = [
            'title' => $event->title(),
            'commentCount' => 0
        ];

        return $data;
    }

    private function whenArticleTitleChanged($data, ArticleTitleChanged $event)
    {
        $data[(string)$event->articleId()]['title'] = $event->title();

        return $data;
    }

    private function whenCommented($data, Commented $event)
    {
        $data[(string)$event->articleId()]['commentCount']++;

        return $data;
    }
}
