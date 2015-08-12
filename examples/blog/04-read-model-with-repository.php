<?php

use PhpInPractice\EventStore\Example\Blog\ArticleReadRepository;
use PhpInPractice\EventStore\Example\Blog\ArticleTreeProjector;

require_once '01-initialize-event-sourcing.php';

$articleTree = new ArticleTreeProjector();
$emitter->subscribe($articleTree);

require_once '02-post-articles-with-comments.php';

$repository = new ArticleReadRepository($articleTree);
$articles = $repository->fetchAll();
$articleId = key($articles);
$article = $repository->fetch(key($articles));

echo "===========================================================\n";
echo "== Complete tree                                         ==\n";
echo "===========================================================\n";
var_dump($articles);
echo "===========================================================\n";

echo "\n";

echo "===========================================================\n";
echo "== Single article ({$article->id}) ==\n";
echo "===========================================================\n";
var_dump($article);
echo "===========================================================\n";


