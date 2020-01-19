<?php


namespace App\Models\entities\Feed;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;

class Feed
{
    /**
     * @var EntityID
     */
    private $entityID;
    /**
     * @var string
     */
    private $rss;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var NewsSite
     */
    private $newsSite;

    public function __construct(EntityID $entityID, string $rss, Category $category, NewsSite $newsSite)
    {
        $this->entityID = $entityID;
        $this->rss = $rss;
        $this->category = $category;
        $this->newsSite = $newsSite;
    }

    /**
     * @return string
     */
    public function getRss(): string
    {
        return $this->rss;
    }

    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @return NewsSite
     */
    public function getNewsSite(): NewsSite
    {
        return $this->newsSite;
    }
}
