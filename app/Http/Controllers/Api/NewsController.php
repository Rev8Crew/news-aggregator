<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\entities\News\News;
use App\Models\services\NewsService;

use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;

class NewsController extends Controller
{
    //

    /**
     * @var NewsService
     */
    private $newsService;


    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index() {
        $results = \request()->only(['title', 'text', 'release_at', 'source', 'site_id', 'category_id']);
        $user = request()->header('token');

        /* Если юзер просматривает новости по определенному сайту, то добавляем в избранное */
        if ($user && request('site_id')) {
            $results = array_merge($results, [ 'user' => $user]);
        }

        /** Фильтруем результаты */
        $results = $this->newsService->filter($results);

        $data = [];
        /** Сортируем по избранному юзера, если необходимо */
        /** @var News $item */
        foreach ($this->newsService->sortByUserFavorite($results, $user) as $item) {
            $data[] = new NewsResource($item);
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function search() {
        $conn = new Connection();
        $conn->setParams(array('host' => 'localhost', 'port' => 9306));

        $search = request('search', '');
        $user = request()->header('token');

        $result = (new SphinxQL($conn))->select("entity_id", "title", "text", "SNIPPET(title, '$search') as tt", "SNIPPET(text, '$search') as te", "release_at", "source", "site_id", "category_id")->from('catalog')->match('title', $search)->match('text', $search)->execute();

        $results = array_map( function ($res) { return $this->newsService->convertFromSphinx((object) $res); }, $result->fetchAllAssoc());

        $data = [];
        /** Сортируем по избранному юзера, если необходимо */
        /** @var News $item */
        foreach ($this->newsService->sortByUserFavorite($results, $user) as $item) {
            $data[] = new NewsResource($item);
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
