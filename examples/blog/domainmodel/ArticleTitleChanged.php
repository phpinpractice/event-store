<?php
namespace PhpInPractice\EventStore\Example\Blog;

class ArticleTitleChanged
{
    private $title;

    /** @var ArticleId */
    private $articleId;

    public function __construct(ArticleId $articleId, $title)
    {
        $this->articleId = $articleId;
        $this->title = $title;
    }

    public function articleId()
    {
        return $this->articleId;
    }

    public function title()
    {
        return $this->title;
    }

    public function toArray()
    {
        return [
            'id'    => (string)$this->articleId,
            'title' => $this->title
        ];
    }

    public static function fromArray(array $data)
    {
        return new static(ArticleId::fromString($data['id']), $data['title']);
    }
}
