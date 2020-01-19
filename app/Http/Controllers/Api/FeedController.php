<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use App\Models\entities\Feed\Feed;
use App\Models\entities\News\News;
use App\Models\services\CategoryService;
use App\Models\services\FeedService;
use App\Models\services\NewsService;
use App\Models\services\NewsSiteService;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * @var FeedService
     */
    private $feedService;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var NewsSiteService
     */
    private $newsSiteService;

    public function __construct(FeedService $feedService, CategoryService $categoryService, NewsSiteService $newsSiteService )
    {
        $this->feedService = $feedService;
        $this->categoryService = $categoryService;
        $this->newsSiteService = $newsSiteService;
    }

    public function index() {

        $filter = $this->feedService->filter(\request()->all());
        return response()->json($filter, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function add() {

        $this->validate(\request(), [
            'rss_url' => 'required|url',
            'category'=> 'required',
            'newssite' => 'required'
        ]);

        $category = $this->categoryService->createOrFirst(\request('category'));
        $site = $this->newsSiteService->findOrNull(\request('newssite'));
        $feed = $this->feedService->createOrFirst( \request('rss_url'), $category, $site);

        return response('OK...');
    }

}
