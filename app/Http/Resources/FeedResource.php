<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'rss' => $this->getRss(),
          'category' => new CategoryResource($this->getCategory()),
          'site_url' => $this->getNewsSite()->getSiteUrl()
        ];
    }
}
