<?php

namespace App\Console\Commands;

use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\Feed\Feed;
use App\Models\entities\Source\Source;
use App\Models\repositories\FeedRepository;
use App\Models\services\CategoryService;
use App\Models\services\FeedService;
use App\Models\services\NewsService;
use App\Models\services\SourceService;
use App\Models\News;
use Illuminate\Console\Command;

class ParseRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:parse_rss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var FeedService
     */
    private $feedService;
    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * Create a new command instance.
     *
     * @param FeedService $feedService
     * @param NewsService $newsService
     */
    public function __construct(FeedService $feedService, NewsService $newsService)
    {
        parent::__construct();
        $this->feedService = $feedService;
        $this->newsService = $newsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Feed $feed */
        foreach ($this->feedService->all() as $feed) {
            $parsed = $this->feedService->parse($feed);

            foreach ($parsed as $news) {
               $q = $this->newsService->createFromArray($news, $feed->getNewsSite(), $feed->getCategory());
            }
        }


        print_r($q);
    }
}
