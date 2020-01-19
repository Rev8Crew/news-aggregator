<?php

use Illuminate\Database\Seeder;

class RssTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\Models\services\NewsSiteService $newsSiteService */
        $newsSiteService = app(\App\Models\services\NewsSiteService::class);

        /** @var \App\Models\services\FeedService $feedService */
        $feedService = app(\App\Models\services\FeedService::class);

        /** @var \App\Models\services\CategoryService $categoryService */
        $categoryService = app(\App\Models\services\CategoryService::class);

        #$siteFirst = $newsSiteService->createOrFirst('https://3dnews.ru', '3DNews.ru');
        $siteSecond = $newsSiteService->createOrFirst('https://www.ixbt.com', 'IXBT');

        #$categoryFirst = $categoryService->createOrFirst('IT');
        $categorySecond = $categoryService->createOrFirst('Main');

        #$feedService->createOrFirst('https://3dnews.ru/news/main/rss', $categoryFirst, $siteFirst);
        $feedService->createOrFirst('https://www.ixbt.com/export/news.rss', $categorySecond, $siteSecond);

        /** @var \App\Models\services\UserService $userService */
        $userService = app(\App\Models\services\UserService::class);

        $user = $userService->createOrFirst('Name', 'pass', [ $categorySecond ], []);
        $userService->addCategory($user, $categorySecond);
    }
}
