<?php


namespace App\Models\repositories;


use App\Models\entities\EntityID;
use App\Models\entities\View\View;
use App\Models\Hydrator;

use Illuminate\Support\Facades\DB;

class ViewRepository extends BaseRepository
{
    static $table = 'view';
    /**
     * @var NewsSiteRepository
     */
    private $newsSiteRepository;

    public function __construct(Hydrator $hydrate, NewsSiteRepository $newsSiteRepository)
    {
        $this->hydrate = $hydrate;
        $this->newsSiteRepository = $newsSiteRepository;
    }

    public function findBySite($siteID)
    {
        $duplicate = DB::table(static::$table)->where([
            'site_id' => new EntityID($siteID)
            ])->first();

        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function get(EntityID $id): View
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }

    public function convertFromDb($result): View
    {
        return $this->hydrate->hydrate(View::class, [
            'entityID'  => new EntityID($result->entity_id),
            'newsSite'  => $this->newsSiteRepository->get( new EntityID($result->site_id)),
            'count'     => $result->count
        ]);
    }

    public function add(View $item): void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id' => $item->getEntityID()->getId(),
                'site_id'   => $item->getNewsSite()->getEntityID()->getId(),
                'count'     => $item->getCount()
            ]);
    }

    public function save(View $item): void
    {
        DB::table(static::$table)->where('entity_id', $item->getEntityID()->getId())->update([
            'site_id'   => $item->getNewsSite()->getEntityID()->getId(),
            'count'     => $item->getCount()
        ]);
    }
}
