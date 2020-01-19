<?php


namespace App\Models\repositories;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\Hydrator;
use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository
{
    static $table = 'categories';

    public function __construct( Hydrator $hydrate)
    {
        $this->hydrator = $hydrate;
    }

    public function findByName($name)
    {
        $duplicate = DB::table(static::$table)->where('name', $name)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function convertFromDb($result)
    {
        return $this->hydrator->hydrate(Category::class, [
            'entityID' => new EntityID($result->entity_id),
            'name' => $result->name,
        ]);
    }

    public function get(EntityID $id): Category
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }
    public function add(Category $item) : void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id' =>  $item->getEntityID()->getId(),
                'name' =>    $item->getName()
            ]);
    }
}
