<?php


namespace App\Models\repositories;


use App\Models\entities\EntityID;
use App\Models\Hydrator;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    static $table = '';

    /**
     * @var Hydrator
     */
    protected $hydrate;

    abstract function convertFromDb($result);

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->filter([], '', '');
    }

    public function findByID( $id ) {
        $duplicate = DB::table(static::$table)->where('entity_id', $id)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function filter($columns, $order, $order_type) {
        $sql = DB::table(static::$table);

        foreach ($columns as $column=>$value) {
            $sql = $this->addWhereColToSql($sql, $column, $value);
        }

        if ($order) {
            $sql = $sql->orderBy( $order, $order_type);
        }

        #DB::enableQueryLog();
        $sql = $sql->get()->toArray();

        #print_r(DB::getQueryLog());

        return array_map(function ($result) {
            return $this->convertFromDb($result);
        }, $sql);
    }

    protected function addWhereColToSql($sql, $col, $val){
        return $sql->where($col, $val);
    }

    public function removeByID( EntityID $entityID) {
        DB::table(static::$table)->where('entity_id', $entityID->getId())->delete();
    }

    public function getByColumn( $column, $value) {
        $result = DB::table(static::$table)->where($column, $value)->first();
        return $this->convertFromDb($result);
    }
}
