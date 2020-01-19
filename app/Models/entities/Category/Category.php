<?php


namespace App\Models\entities\Category;


use App\Models\entities\EntityID;

class Category
{
    /**
     * @var EntityID
     */
    private $entityID;
    private $name;

    public function __construct( EntityID $entityID, string $name)
    {
        $this->entityID = $entityID;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }
}
