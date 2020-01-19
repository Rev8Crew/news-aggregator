<?php


namespace Tests\Unit\Feed;


use App\Models\entities\EntityID;
use App\Models\entities\Feed;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function testUnique() {
        $this->expectExceptionMessage('Rss url must be unique');
        $feed = new Feed( $id = new EntityID(6), "http://unique.com");


    }
}
