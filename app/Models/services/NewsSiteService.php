<?php


namespace App\Models\services;

use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\repositories\NewsSiteRepository;
use Carbon\Carbon;

class NewsSiteService
{
    /**
     * @var FeedRepository
     */
    private $newsSiteRepository;

    public function __construct(NewsSiteRepository $newsSiteRepository)
    {
        $this->newsSiteRepository = $newsSiteRepository;
    }

    public function findOrNull($param) {
        $duplicate = $this->newsSiteRepository->findByID($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->newsSiteRepository->findBySiteUrl($param);

        if ($duplicate) {
            return $duplicate;
        }

        return null;
    }

    public function createOrFirst($siteUrl, $name ): NewsSite
    {
        $duplicate = $this->findOrNull($siteUrl);

        if ($duplicate) {
            return $duplicate;
        }

        $item = new NewsSite(
            EntityID::nextId(),
            $siteUrl,
            $name
        );

        $this->newsSiteRepository->add($item);
        return $item;
    }

    public function all()
    {
        return $this->newsSiteRepository->all();
    }
}
