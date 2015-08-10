<?php
use PhpInPractice\EventStore\Aggregate\Emitter;
use PhpInPractice\EventStore\Aggregate\WriteRepository;
use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Example\Blog\ArticleListing;
use PhpInPractice\EventStore\Storage\InMemory;
use PhpInPractice\EventStore\Example\Blog\Article;
use PhpInPractice\EventStore\Example\Blog\ArticleRepository;

include __DIR__ . '/../../vendor/autoload.php';

$articleListing = new ArticleListing();

$eventStore = new EventStore(new InMemory());
$emitter    = new Emitter();
$emitter->subscribe($articleListing);
$writeRepository = new ArticleRepository(WriteRepository::create($eventStore, Article::class, $emitter));

$article1 = Article::post('An amazing introduction into Event Sourcing', 'Just look at all these examples!');
    $article1->comment('Mike van Riel', 'And I should be able to add a comment');
    $article1->comment('Mike van Riel', 'Let\'s add a second and see that the count is two');
$article2 = Article::post('Information is written using a Write Repository', 'Just look at all these examples!');
$article3 = Article::post('And this automatically populates Projections', 'Just look at all these examples!');
$article4 = Article::post('This happens because the WriteRepository calls the Emitter', 'Just look at all these examples!');
$article5 = Article::post('And the emitter applies every event to a stored representation', 'Just look at all these examples!');

$writeRepository->persist($article1);
$writeRepository->persist($article2);
$writeRepository->persist($article3);
$writeRepository->persist($article4);
$writeRepository->persist($article5);

$articles = $articleListing->getProjection();
foreach ($articles as $id => $article) {
    echo sprintf("%s. %s[%d]\n", substr($id, -6), $article['title'], $article['commentCount']);
}
