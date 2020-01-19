<?php


namespace App\Models\entities\News;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;
use Carbon\Carbon;

class News
{
    /**
     * @var EntityID
     */
    private $entityID;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $text;
    /**
     * @var Carbon
     */
    private $releaseDate;
    /**
     * @var string
     */
    private $source;
    /**
     * @var NewsSite
     */
    private $newsSite;
    /**
     * @var Category
     */
    private $category;

    public function __construct(EntityID $entityID, string $title, string $text, Carbon $dateTime, string $source, NewsSite $newsSite, Category $category)
    {

        $this->entityID = $entityID;
        $this->title = $title;
        $this->text = $text;
        $this->releaseDate = $dateTime;
        $this->source = $source;
        $this->newsSite = $newsSite;
        $this->category = $category;
    }

    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Carbon
     */
    public function getReleaseDate(): Carbon
    {
        return $this->releaseDate;
    }

    /**
     * @param Carbon $releaseDate
     */
    public function setReleaseDate(Carbon $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return NewsSite
     */
    public function getNewsSite(): NewsSite
    {
        return $this->newsSite;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}
