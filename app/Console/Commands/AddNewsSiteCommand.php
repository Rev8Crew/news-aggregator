<?php

namespace App\Console\Commands;

use App\Models\services\NewsSiteService;
use Illuminate\Console\Command;
use mysql_xdevapi\Exception;

class AddNewsSiteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_site {url : site url} {name : site name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var NewsSiteService
     */
    private $newsSiteService;

    /**
     * Create a new command instance.
     *
     * @param NewsSiteService $newsSiteService
     */
    public function __construct(NewsSiteService $newsSiteService)
    {
        parent::__construct();
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
            $name = $this->getSiteName();

            $site = $this->newsSiteService->createOrFirst($url, $name);

        } catch ( \Exception $exception) {
            $this->error('Exception:'.$exception->getMessage());
            return 1;
        }

        print_r($site);
        $this->info('Success create NewsSite');
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

    private function getSiteName() {
        $name = $this->argument('name');

        if ( !$name || strlen($name) < 3) {
            throw new \Exception("Invalid name '$name'");
        }

        return $name;
    }
}
