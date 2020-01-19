<?php


namespace App\Models\repositories;

use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\Feed\Feed;
use App\Models\Hydrator;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class FeedRepository extends BaseRepository
{
    static $table = 'feeds';
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var NewsSiteRepository
     */
    private $newsSiteRepository;

    public function __construct(Hydrator $hydrate, CategoryRepository $categoryRepository, NewsSiteRepository $newsSiteRepository)
    {
        $this->hydrate = $hydrate;
        $this->categoryRepository = $categoryRepository;
        $this->newsSiteRepository = $newsSiteRepository;
    }

    public function findByRssUrl($rss_url)
    {
        $duplicate = DB::table(static::$table)->where('rss_url', $rss_url)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function get(EntityID $id): Feed
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }

    public function convertFromDb($result): Feed
    {
        return $this->hydrate->hydrate(Feed::class, [
            'entityID' => new EntityID($result->entity_id),
            'rss' => $result->rss_url,
            'category' => $this->categoryRepository->get(new EntityID($result->category_id)),
            'newsSite' => $this->newsSiteRepository->get(new EntityID($result->site_id))
        ]);
    }

    public function add(Feed $item): void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id' => $item->getEntityID()->getId(),
                'rss_url' => $item->getRss(),
                'category_id' => $item->getCategory()->getEntityID()->getId(),
                'site_id'  => $item->getNewsSite()->getEntityID()->getId()
            ]);
    }
}
