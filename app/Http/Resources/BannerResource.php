<?php

namespace App\Http\Resources;

use App\Models\MainBanner1;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        $image = $this->image;
        if (!$image){
            $image = "";
        }
        else{
            $image = "/storage/".$image;
        }

        return [
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
            'image' => $image
        ];

    }
}
