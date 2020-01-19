<?php


namespace App\Models\services;

use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\News\News;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\repositories\NewsRepository;
use App\Models\repositories\NewsSiteRepository;
use Carbon\Carbon;

class NewsService
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var NewsSiteService
     */
    private $newsSiteService;

    public function __construct(NewsRepository $newsRepository, UserService $userService, NewsSiteService $newsSiteService   )
    {
        $this->newsRepository = $newsRepository;
        $this->userService = $userService;
        $this->newsSiteService = $newsSiteService;
    }

    public function findOrNull($param) {
        $duplicate = $this->newsRepository->findByID($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->newsRepository->findByTitle($param);

        if ($duplicate) {
            return $duplicate;
        }

        return null;
    }

    public function filter( array $columns, string $order = '', string $order_type = 'ASC') {

        if ( array_key_exists('release_at', $columns) ) {
            $date_array = [ 'day', 'week', 'month'];

            if ( in_array($columns['release_at'], $date_array)) {
                $columns['release_at'] = $this->filterByDateWord($columns['release_at']);
            } else {
                $columns['release_at'] = Carbon::parse($columns['release_at']);
            }
        }

        // чтобы показывать избранные
        if ( array_key_exists('site_id', $columns) && array_key_exists('user', $columns)) {
            $user = $this->userService->findOrNull($columns['user']);
            unset($columns['user']);

            $this->userService->addToViews($user, $this->newsSiteService->findOrNull($columns['site_id']));
        }

        return $this->newsRepository->filter($columns, $order, $order_type);
    }

    public function sortByUserFavorite(array $results, $user = null) {
        if ( !$user ) {
            return $results;
        }

        $user = $this->userService->findOrNull($user);
        $views = $this->userService->getViews($user);

        if (!$views) {
            return $results;
        }

        /**
         * @var  News $result
         */
        foreach ($results as &$result) {
            $viewKey =$result->getNewsSite()->getEntityID()->getId();

            if ( array_key_exists($viewKey, $views) === false) {
                $result->count = 0;
                continue;
            }

            $result->count = $views[$viewKey]->getCount();
        }

        usort( $results, function ($a, $b) { return $a->count < $b->count; });
        return $results;
    }

    public function createOrFirst($title, $text, Carbon $dateTime, string $source, NewsSite $newsSite, Category $category) : News {
        $duplicate = $this->findOrNull($title);

        if ($duplicate) {
            return $duplicate;
        }

        $news = new News(
            EntityID::nextId(),
            $title,
            $text,
            $dateTime,
            $source,
            $newsSite,
            $category
        );

        $this->newsRepository->add($news);
        return $news;
    }

    public function createFromArray( array $array, NewsSite $newsSite, Category $category) : News {
        return $this->createOrFirst( $array['title'], $array['text'], $array['release_at'], $array['source'], $newsSite, $category);
    }

    private function filterByDateWord( $word = '' ) {
        $time = Carbon::now();

        if ($word == 'day') {
            return [$time->copy()->startOfDay(), $time->copy()->endOfDay()];
        } else if ( $word == 'week') {
            return [$time->copy()->startOfWeek(), $time->copy()->endOfWeek()];
        } else if ( $word == 'month') {
            return [$time->copy()->startOfMonth(), $time->copy()->endOfMonth()];
        }

        return [$time->copy()->startOfDay(), $time->copy()->endOfDay()];
    }

    public function all() {
        return $this->newsRepository->all();
    }

    public function convertFromSphinx($result) : News {
        $news = $this->newsRepository->convertFromDb($result);
        $news->matchTitle = $result->tt;
        $news->matchText = $result->te;
        return $news;
    }
}
