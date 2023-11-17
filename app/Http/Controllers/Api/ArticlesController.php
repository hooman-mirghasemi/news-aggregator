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

    public function show(Article $article): JsonResponse
    {
        return $this->respondOk(['articles' => new ArticleResource($article)]);
    }
}
