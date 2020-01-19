<?php

namespace App\Console\Commands;

use App\Models\services\CategoryService;
use App\Models\services\FeedService;
use App\Models\services\NewsSiteService;
use Illuminate\Console\Command;

class AddFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_feed {url : rss url page} {category : id or string to create} {site : newssite url or id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var FeedService
     */
    private $feedService;
    /**
     * @var NewsSiteService
     */
    private $newsSiteService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CategoryService $categoryService, NewsSiteService $newsSiteService, FeedService $feedService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->feedService = $feedService;
        $this->newsSiteService = $newsSiteService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $url = $this->getUrl();
            $category = $this->getCategory();
            $site = $this->getSiteUrl();

            $category = $this->categoryService->createOrFirst($category);
            $site = $this->newsSiteService->createOrFirst($site, $site);
            $feed = $this->feedService->createOrFirst($url, $category, $site);

        } catch ( \Exception $exception) {
            $this->error('Exception:'.$exception->getMessage());
            return 1;
        }

        print_r($feed);
        $this->info('Success create Feed');
        return 0;
    }

    private function getUrl()
    {
        $url = $this->argument('url');

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception("Invalid URL '$url'");
        }

        return $url;
    }

    private function getCategory()
    {
        $category = $this->argument('category');

        if (!$category ) {
            throw new \Exception("Invalid category '$category'");
        }

        return $category;
    }

    private function getSiteUrl()
    {
        $url = $this->argument('site');

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception("Invalid URL '$url'");
        }

        return $url;
    }
}
