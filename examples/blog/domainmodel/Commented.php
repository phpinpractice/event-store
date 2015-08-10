<?php
namespace PhpInPractice\EventStore\Example\Blog;

class Commented
{
    private $body;

    /** @var ArticleId */
    private $articleId;

    /** @var string */
    private $commentId;

    /** @var string */
    private $author;

    public function __construct(ArticleId $articleId, $commentId, $body, $author)
    {
        $this->articleId = $articleId;
        $this->body      = $body;
        $this->commentId = $commentId;
        $this->author    = $author;
    }

    public function articleId()
    {
        return $this->articleId;
    }

    public function commentId()
    {
        return $this->commentId;
    }

    public function body()
    {
        return $this->body;
    }

    public function author()
    {
        return $this->author;
    }

    public function toArray()
    {
        return [
            'id'        => (string)$this->articleId,
            'commentId' => $this->commentId,
            'body'      => $this->body,
            'author'    => $this->author
        ];
    }

    public static function fromArray(array $data)
    {
        return new static(ArticleId::fromString($data['id']), $data['commentId'], $data['body'], $data['author']);
    }
}
