<?php


namespace Tests\Unit\News;

use App\Models\entities\Category\Category;
use App\Models\entities\DateTime;
use App\Models\entities\EntityID;
use App\Models\entities\News\News;
use App\Models\entities\News\ReleaseDate;
use App\Models\entities\News\Text;


use App\Models\entities\Source\Source;
use Carbon\Carbon;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess() {
        $news = new News(
            $id = new EntityID(1),
            $title = "someTitle",
            $text = "someText",
            $releaseDate = new Carbon('2019-01-01 00:00'),
            $category = new Category( new EntityID(2), "Name"),
            $source = new Source(3, 'http://example.com')
        );

        $this->assertEquals($id, $news->getEntityID());
        $this->assertEquals($title, $news->getTitle());
        $this->assertEquals($text, $news->getText());
        $this->assertEquals($releaseDate, $news->getReleaseDate());
        $this->assertEquals($category, $news->getCategory());
        $this->assertEquals($source, $news->getSource());
    }

    public function testWithoutTitleAndText() {
        $this->expectExceptionMessage('Title\Text must be not empty');

        new News(
            $id = new EntityID(1),
            $title = "",
            $text = "",
            $releaseDate = new Carbon('2019-01-01 00:00'),
            $category = new Category( new EntityID(2), "Name"),
            $source = new Source(3, 'http://example.com')
        );
    }


}
