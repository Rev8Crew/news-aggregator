<?php


namespace App\Models\repositories;


use App\Models\entities\EntityID;
use App\Models\entities\News\News;
use App\Models\Hydrator;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewsRepository extends BaseRepository
{
    static $table = 'news';

    /**
     * @var NewsSiteRepository
     */
    private $newsSiteRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct( Hydrator $hydrate, NewsSiteRepository $newsSiteRepository, CategoryRepository $categoryRepository)
    {
        $this->hydrate = $hydrate;
        $this->newsSiteRepository = $newsSiteRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function findByTitle($title)  {
        $duplicate = DB::table(static::$table)->where('title', $title)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function get(EntityID $id): News
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }

    public function convertFromDb($result) : News {
        return $this->hydrate->hydrate(News::class, [
            'entityID' => new EntityID($result->entity_id),
            'title' => $result->title,
            'text'  => $result->text,
            'releaseDate' => Carbon::parse($result->release_at),
            'source' => $result->source,
            'newsSite' => $this->newsSiteRepository->get( new EntityID($result->site_id)),
            'category' => $this->categoryRepository->get( new EntityID($result->category_id)),
        ]);
    }

    public function add(News $item) : void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id' =>  $item->getEntityID()->getId(),
                'title' =>    $item->getTitle(),
                'text' =>       $item->getText(),
                'release_at' => $item->getReleaseDate(),
                'source'    => $item->getSource(),
                'site_id'   => $item->getNewsSite()->getEntityID()->getId(),
                'category_id' => $item->getCategory()->getEntityID()->getId(),
            ]);
    }

    protected function addWhereColToSql($sql, $col, $val){

        if( $col == 'release_at') {
            if ( $val instanceof Carbon) {
                return $sql->whereDate('release_at', $val);
            }

            $startTime = $val[0];
            $endTime = $val[1];
            return $sql->whereBetween('release_at', [$startTime, $endTime]);
        }

        return $sql->where($col, $val);
    }
}
