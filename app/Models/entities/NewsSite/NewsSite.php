<?php


namespace App\Models\entities\NewsSite;


use App\Models\entities\EntityID;

class NewsSite
{
    /**
     * @var EntityID
     */
    private $entityID;
    /**
     * @var string
     */
    private $siteUrl;
    /**
     * @var string
     */
    private $name;

    public function __construct(EntityID $entityID, string $siteUrl, string $name)
    {
        $this->entityID = $entityID;
        $this->siteUrl = $siteUrl;
        $this->name = $name;
    }

    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }

    /**
     * @return string
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
