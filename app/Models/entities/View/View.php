<?php


namespace App\Models\entities\View;


use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\entities\User\User;

class View
{
    /**
     * @var EntityID
     */
    private $entityID;
    /**
     * @var NewsSite
     */
    private $newsSite;
    /**
     * @var int
     */
    private $count;

    public function __construct(EntityID $entityID, NewsSite $newsSite, int $count)
    {

        $this->entityID = $entityID;
        $this->newsSite = $newsSite;
        $this->count = $count;
    }

    /**
     * @return NewsSite
     */
    public function getNewsSite(): NewsSite
    {
        return $this->newsSite;
    }

    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    public function addCount() {
        return $this->count++;
    }
}
