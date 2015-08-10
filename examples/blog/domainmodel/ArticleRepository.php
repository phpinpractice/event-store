<?php
namespace PhpInPractice\EventStore\Example\Blog;

use PhpInPractice\EventStore\Aggregate\WriteRepository;
use PhpInPractice\EventStore\Stream;

class ArticleRepository
{
    /** @var WriteRepository */
    private $repository;

    public function __construct(WriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find(ArticleId $articleId)
    {
        return $this->repository->load((string)$articleId);
    }

    public function persist(Article $article)
    {
        $this->repository->persist($article);
    }
}
