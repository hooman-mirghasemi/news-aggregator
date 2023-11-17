<?php

namespace App\NewsReaders\Drivers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\NewsReaders\Abstracts\Driver;
use Illuminate\Support\Facades\Http;

class WorldNews extends Driver
{
    private string $webServiceBaseUrl = 'https://api.worldnewsapi.com';
    private bool $isDeveloperAccount = true;

    public function __construct(protected array $settings)
    {
        if (! data_get($this->settings, 'apikey')) {
            throw new \Exception('You should define WORLDNEWS_API_KEY in your .env file');
        }
        $this->apiKey = data_get($this->settings, 'apikey');
    }
    public function pullNewsToDb(): void
    {
        $url = $this->webServiceBaseUrl.'/search-news?api-key='.$this->apiKey;
        $url .= '&language=en';
        $url .= '&earliest-publish-date=' . $this->from->format('Y-m-d') . '&latest-publish-date=' . $this->from->addDay()->format('Y-m-d');
        $url .= '&number=100';
        $url .= '&text=' . $this->category->name;
        $this->processOnePageResults($url, $this->category);

    }

    /**
     * @param string $url
     * @param mixed $category
     * @return void
     * @throws \Exception
     */
    private function processOnePageResults(string $url, Category $category, int $pageNumber = 1): void
    {
        $fullUrl = $url . '&offset=' . (($pageNumber - 1) * 100);
        $response = Http::get($fullUrl);
        if (! $response->json('news')) {
            throw new \Exception('An error occurred through calling world news:' . $response->body());
        }
        $totalCount = $response->json('available');
        $totalPage = ceil($totalCount / 100);
        foreach ($response->json('news') as $jsonArticle) {
            $this->insertArticleToDb($jsonArticle, $category);
        }
        if ($totalPage > $pageNumber && !$this->isDeveloperAccount) {
            $this->processOnePageResults($url, $category, ++$pageNumber);
        }
    }

    /**
     * @param mixed $jsonArticle
     * @param mixed $category
     * @return void
     */
    private function insertArticleToDb(mixed $jsonArticle, Category $category): void
    {
        if (isset($jsonArticle['author'])) {
            $author = Author::firstOrCreate([
                'name' => $jsonArticle['author'],
            ]);
        } else {
            $author = null;
        }

        $domain = parse_url( $jsonArticle['url']);
        $source = Source::firstOrCreate([
            'name' => $domain['host'],
        ]);

        Article::firstOrCreate([
            'title' => $jsonArticle['title'],
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ],[
            'title' => $jsonArticle['title'],
            'content' => $jsonArticle['text'],
            'image' => $jsonArticle['image'],
            'published_at' =>  $jsonArticle['publish_date'],
            'data_source' => 'WorldNews',
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ]);
    }


}
