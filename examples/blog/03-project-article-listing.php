<?php

use PhpInPractice\EventStore\Example\Blog\ArticleListingProjector;

require_once '01-initialize-event-sourcing.php';

$articleListing = new ArticleListingProjector();
$emitter->subscribe($articleListing);

require_once '02-post-articles-with-comments.php';

$articles = $articleListing->articles();
foreach ($articles as $id => $article) {
    echo sprintf("%s. %s[%d]\n", substr($id, -6), $article['title'], $article['commentCount']);
}
