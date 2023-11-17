<?php

namespace App\NewsReaders\Drivers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\NewsReaders\Abstracts\Driver;
use Illuminate\Support\Facades\Http;

class NewsApi extends Driver
{

    private string $webServiceBaseUrl = 'https://newsapi.org/v2';
    public function __construct(protected array $settings)
    {
        if (! data_get($this->settings, 'apikey')) {
            throw new \Exception('You should define NEWSAPI_API_KEY in your .env file');
        }
        $this->apiKey = data_get($this->settings, 'apikey');
    }

    public function pullNewsToDb(): void
    {
        $url = $this->webServiceBaseUrl.'/top-headlines?apiKey='.$this->apiKey;
        $url .= '&from=' . $this->from . '&to=' . $this->from->addDay();
        $url .= '&language=en&pageSize=100';
        $url .= '&category=' . $this->category->name;
        $this->processOnePageResults($url, $this->category);

    }

    /**
     * @param mixed $jsonArticle
     * @param mixed $category
     * @return void
     */
    private function insertArticleToDb(mixed $jsonArticle, Category $category): void
    {
        if ($jsonArticle['content'] == null) {
            return;
        }
        if ($jsonArticle['author']) {
            $author = Author::firstOrCreate([
                'name' => $jsonArticle['author'],
            ]);
        } else {
            $author = null;
        }

        $source = Source::firstOrCreate([
            'name' => $jsonArticle['source']['name'],
        ]);

        Article::firstOrCreate([
            'title' => $jsonArticle['title'],
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ],[
            'title' => $jsonArticle['title'],
            'content' => $jsonArticle['content'],
            'image' => $jsonArticle['urlToImage'],
            'published_at' => $jsonArticle['publishedAt'] == '1970-01-01 00:00:00' ? $jsonArticle['publishedAt'] : now()->subDay(),
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * @param string $url
     * @param mixed $category
     * @return void
     * @throws \Exception
     */
    private function processOnePageResults(string $url, Category $category, int $pageNumber = 1): void
    {
        $url .= '&page=' . $pageNumber;
        $response = Http::get($url);
        if ($response->json('status') != 'ok') {
            throw new \Exception('An error occurred through calling newsApi');
        }
        $totalCount = $response->json('totalResults'); //@todo fetch other pages
        $totalPage = ceil($totalCount / 100);
        foreach ($response->json('articles') as $jsonArticle) {
            if ($jsonArticle['title'] == '[Removed]') {
                continue;
            }
            $this->insertArticleToDb($jsonArticle, $category);
        }
        if ($totalPage > $pageNumber) {
            $this->processOnePageResults($url, $category, ++$pageNumber);
        }
    }
}
