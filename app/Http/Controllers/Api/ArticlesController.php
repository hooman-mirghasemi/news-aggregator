<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Ttaits\ApiResponseHelpers;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArticlesController
{
    use ApiResponseHelpers;

    /**
     * articles list
     *
     * This api return articles paginate also you can set filter or user preferences for order it
     *
     * @queryParam filter[search] string search in title and content of articles. Example:sport
     * @queryParam filter[category_id] integer Filter all articles of category. Example:1
     * @queryParam filter[source_id] integer Filter all articles in specific source. Example:2
     * @queryParam filter[from_date] date Filter all articles published after date. Example:2023-11-15
     * @queryParam filter[to_date] date Filter all articles published before date. Example:2023-11-16
     * @queryParam preferences[source_id] order for user preferences source, (first show articles from these sources then other articles). Example:8,9
     * @queryParam preferences[category_id] order for user preferences categories, (first show articles from these categories then other articles). Example:1,2
     * @queryParam preferences[author_id] order for user preferences author, (first show articles from these authors then other articles). Example:51
     *
     * @apiResourceCollection App\Http\Resources\ArticleCollection
     *
     * @apiResourceModel App\Models\Article paginate=10
     *
     */
    public function index(): JsonResponse
    {
        $articlesQuery = QueryBuilder::for(Article::class)
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('source_id'),
                AllowedFilter::callback('from_date', function ($query, $value) {
                    $query->where('published_at', '>=', Carbon::parse($value));
                }),
                AllowedFilter::callback('to_date', function ($query, $value) {
                    $query->where('published_at', '<=', Carbon::parse($value));
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('title', 'like', '%'.$value.'%');
                    $query->orWhere('content', 'like', '%'.$value.'%');
                }),
            ])
            ->with(['author', 'source', 'category']);

        if (request()->has('preferences')) {
            $preferences = request()->get('preferences');
            if (isset($preferences['source_id'])) {
                $articlesQuery->orderByRaw("FIELD(source_id,".$preferences['source_id'].") DESC");
            }
            if (isset($preferences['category_id'])) {
                $articlesQuery->orderByRaw("FIELD(category_id,".$preferences['category_id'].") DESC");
            }
            if (isset($preferences['author_id'])) {
                $articlesQuery->orderByRaw("FIELD(author_id,".$preferences['author_id'].") DESC");
            }
        }
        $articles = $articlesQuery->paginate(10);

        return $this->respondOk(['articles' => new ArticleCollection($articles)]);
    }

    /**
     * article show
     *
     * Get full details of an article.
     *
     *
     * @urlParam article_id integer id of the article.
     *
     * @apiResource App\Http\Resources\ArticleResource
     *
     * @apiResourceModel App\Models\Article
     *
     */
    public function show(Article $article): JsonResponse
    {
        return $this->respondOk(['articles' => new ArticleResource($article)]);
    }
}
