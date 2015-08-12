<?php

namespace PhpInPractice\EventStore\Example\Blog;

class ArticleReadRepository
{
    /** @var ArticleTreeProjector */
    private $projector;

    public function __construct(ArticleTreeProjector $projector)
    {
        $this->projector = $projector;
    }

    public function fetchAll()
    {
        return $this->projector->articles();
    }

    public function fetch($id)
    {
        $articles = $this->projector->articles();
        if (!array_key_exists($id, $articles)) {
            return null;
        }

        return $articles[$id];
    }
}
