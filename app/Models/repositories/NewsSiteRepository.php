<?php


namespace App\Models\repositories;

use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\Hydrator;
use Illuminate\Support\Facades\DB;


class NewsSiteRepository extends BaseRepository
{
    static $table = 'news_site';

    public function __construct(Hydrator $hydrate)
    {
        $this->hydrate = $hydrate;
    }

    public function findBySiteUrl($siteUrl)
    {
        $duplicate = DB::table(static::$table)->where('site_url', $siteUrl)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function get(EntityID $id): NewsSite
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }

    public function convertFromDb($result): NewsSite
    {

        return $this->hydrate->hydrate(NewsSite::class, [
            'entityID' => new EntityID($result->entity_id),
            'siteUrl' => $result->site_url,
            'name'  =>$result->name,
        ]);
    }

    public function add(NewsSite $item): void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id' => $item->getEntityID()->getId(),
                'site_url' => $item->getSiteUrl(),
                'name'   => $item->getName()
            ]);
    }
}
