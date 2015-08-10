<?php
namespace PhpInPractice\EventStore\Example\Blog;

class PostedArticle
{
    private $title;
    private $body;

    /** @var ArticleId */
    private $articleId;

    public function __construct(ArticleId $articleId, $title, $body)
    {
        $this->articleId = $articleId;
        $this->title = $title;
        $this->body = $body;
    }

    public function articleId()
    {
        return $this->articleId;
    }

    public function title()
    {
        return $this->title;
    }

    public function body()
    {
        return $this->body;
    }

    public function toArray()
    {
        return [
            'id'    => (string)$this->articleId,
            'title' => $this->title,
            'body'  => $this->body
        ];
    }

    public static function fromArray(array $data)
    {
        return new static(ArticleId::fromString($data['id']), $data['title'], $data['body']);
    }
}
