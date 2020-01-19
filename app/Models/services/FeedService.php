<?php


namespace App\Models\services;

use App\Http\Resources\FeedResource;
use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\Feed\Feed;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\repositories\FeedRepository;
use Carbon\Carbon;

class FeedService
{
    /**
     * @var FeedRepository
     */
    private $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function findOrNull($param) {
        $duplicate = $this->feedRepository->findByID($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->feedRepository->findByRssUrl($param);

        if ($duplicate) {
            return $duplicate;
        }

        return null;
    }

    public function createOrFirst($rssUrl, Category $category, NewsSite $newsSite): Feed
    {

        $duplicate = $this->findOrNull($rssUrl);

        if ($duplicate) {
            return $duplicate;
        }

        $feed = new Feed(
            EntityID::nextId(),
            $rssUrl,
            $category,
            $newsSite
        );

        $this->feedRepository->add($feed);
        return $feed;
    }

    public function filter( array $columns, string $order = '', string $order_type = 'ASC') {
        $filter = $this->feedRepository->filter($columns, $order, $order_type);

        $data = [];
        foreach ($filter as $item) {
            $data[] = new FeedResource($item);
        }

        return $data;
    }

    public function parse(Feed $feed)
    {
        $siteParse = \Feeds::make($feed->getRss());
        $data = [];

        /** Проходим по всем новостям и формируем массив */
        /** @var \SimplePie_Item $feed */
        foreach ($siteParse->get_items() as $feed) {
            $data[] = $this->parseRss($feed);
        }

        return $data;
    }

    private function parseRss(\SimplePie_Item $feed)
    {
        return [
            'title' => $feed->get_title(),
            'text' => $feed->get_description(),
            'source' => $feed->get_permalink(),
            'release_at' => Carbon::createFromFormat(Carbon::RSS, $feed->get_date('')),
        ];
    }

    public function all()
    {
        return $this->feedRepository->all();
    }
}
