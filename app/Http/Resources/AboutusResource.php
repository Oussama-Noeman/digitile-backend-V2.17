<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return
        [
            'member_id' => $this->id,
            'member_name' => $this->name,
//            'member_image' => $this->team_image_attachment,
            'member_position' => $this->position,

        ];
    }
}
