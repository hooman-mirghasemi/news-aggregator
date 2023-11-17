<?php

namespace App\NewsReaders\Drivers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\NewsReaders\Abstracts\Driver;
use Illuminate\Support\Facades\Http;

class TheGuardian extends Driver
{
    private string $webServiceBaseUrl = 'https://content.guardianapis.com';
    public function __construct(protected array $settings)
    {
        if (! data_get($this->settings, 'apikey')) {
            throw new \Exception('You should define THEGUARDIAN_API_KEY in your .env file');
        }
        $this->apiKey = data_get($this->settings, 'apikey');
    }
    public function pullNewsToDb(): void
    {
        $url = $this->webServiceBaseUrl.'/search?api-key='.$this->apiKey;
        $url .= '&show-fields=all';
        $url .= '&from-date=' . $this->from->format('Y-m-d') . '&to-date=' . $this->from->addDay()->format('Y-m-d');
        $url .= '&page-size=100';
        $url .= '&section=' . $this->category->name;
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
        $url .= '&page=' . $pageNumber;
        $response = Http::get($url);

        if ($response->json('response.status') != 'ok') {
            throw new \Exception('An error occurred through calling theGuardian');
        }

        $totalCount = $response->json('response.total');
        $totalPage = ceil($totalCount / 100);
        foreach ($response->json('response.results') as $jsonArticle) {
            $this->insertArticleToDb($jsonArticle, $category);
        }
        if ($totalPage > $pageNumber) {
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
        $author = Author::firstOrCreate([
            'name' => $jsonArticle['fields']['byline'],
        ]);

        $source = Source::firstOrCreate([
            'name' => $jsonArticle['fields']['publication'],
        ]);

        Article::firstOrCreate([
            'title' => $jsonArticle['webTitle'],
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ],[
            'title' => $jsonArticle['webTitle'],
            'content' => $jsonArticle['fields']['bodyText'],
            'image' => $jsonArticle['fields']['thumbnail'],
            'published_at' =>  $jsonArticle['webPublicationDate'],
            'source_id' => $source->id,
            'author_id' => $author?->id,
            'category_id' => $category->id,
        ]);
    }


}
