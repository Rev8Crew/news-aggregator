<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $match = [];
        if (isset($this->matchTitle)) {
            $match['match_title'] = $this->matchTitle;
            $match['match_text'] =  $this->matchText;
        }

        return  array_merge([
            'id'    => $this->getEntityID()->getId(),
            'title' => $this->getTitle(),
            'text'  => $this->getText(),
            'release_at' => $this->getReleaseDate()->format('d-m-y H:I:s'),
            'source' => $this->getSource(),
            'category' => new CategoryResource($this->getCategory())
        ], $match);
    }
}
