<?php

namespace App\Http\Resources;

use App\Models\ProductTemplate;
use App\Models\ProductWishlist;
use App\Models\User;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->name['en'] ?? ''; // Access 'en' value from 'name' field
//        dd($name);
        $description = $this->description['en'] ?? ''; // Access 'en' value from 'description' field

        $isFav = false; // Define how you want to determine if it's a favorite
        $productTemplateId = $this->id;
        $temp = ProductTemplate::where('id',$productTemplateId)->first();

        $user_id = $request->input('user_id');

        if ($user_id) {
            $retailerUser = User::find($user_id);
        } else {
            $retailerUser = null;
        }
        // $productTemplateId = $this->product_tmpl_id;

        if ($retailerUser) {
            $allWishlist = ProductWishlist::where([
                ['user_id', '=', $user_id],
                // ['product_id.product_tmpl_id', '=', $productTemplateId]
            ])
            ->whereHas('productProduct', function ($query) use ($productTemplateId) {
                $query->where('product_tmpl_id', '=', $productTemplateId);
            })
            ->get();
            // dd($allWishlist);
            if(!$allWishlist->isEmpty()){
            $isFav = true;}
        }
        else {
            $allWishlist = null;
        }
        $details = new CustomHelper();
        if (!$this->image) {
            $image = "";
        } else {
            $image = "/storage/" . $this->image;
        }
        return [
                'product_id' => $this->id,
                'product_name' => $name,
                'product_description' => $description,
                'product_image' => $image,
                'price' => $details->getProductTemplatePrice($temp),// $this->lst_price,
                'is_fav' => $isFav,
                'category_id' => $this->categ_id,
            ];
    }
}
