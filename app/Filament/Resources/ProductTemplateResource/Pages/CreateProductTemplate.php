<?php

namespace App\Filament\Resources\ProductTemplateResource\Pages;

use App\Filament\Resources\ProductTemplateResource;
use App\Models\ProductAttribute;
use App\Models\Tenant\ProductAttributeProductTemplateRels;
use App\Models\Tenant\ProductAttributeValue;
use App\Models\Tenant\ProductAttributeValueProductTemplateAttributeLineRels;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplateAttributeLine;
use App\Models\Tenant\ProductTemplateAttributeValue;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class CreateProductTemplate extends CreateRecord
{
    protected static string $resource = ProductTemplateResource::class;
    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->warning()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {

        $attributes = $this->data['attributes'];
        $attributeIds = [];


        foreach ($attributes as $item) {
            $attributeId = $item['attribute_id'];

            if (in_array($attributeId, $attributeIds)) {
                Notification::make()
                    ->warning()
                    ->title('Duplicated Attributes!')
                    ->body('You cannot add two same attributes.')
                    ->persistent()
                    ->send();

                $this->halt();
            }

            $attributeIds[] = $attributeId;
        }
        $this->checkIngredients();
    }


    // protected function afterCreate(): void
    // {
    //     $template = $this->record;
    //     $attributes = $this->data['attributes'];
    //     if (empty($attributes)) {
    //         // dd($template->image);
    //         $product = ProductProduct::create([

    //             'product_tmpl_id' => $template->id,
    //             'categ_id' => $template->categ_id,
    //             'name' => [
    //                 'en' => $template->name['en'],
    //                 'ar' => 'Null',
    //             ],
    //             'drinks_caption' => $template->drinks_caption,
    //             'sides_caption' => $template->sides_caption,
    //             'related_caption' => $template->related_caption,
    //             'liked_caption' => $template->liked_caption,
    //             'desserts_caption' => $template->desserts_caption,
    //             'lst_price' => $template->list_price,
    //             'related_att_values' => "null",
    //             'template_name' => $template->name['en'],
    //             'variant_name' => "null",
    //             'p_t_a_v_line' => "null",
    //             'image' => $template->image,

    //         ]);
    //     } else {

    //         foreach ($attributes as $attribute) {
    //             $line = ProductTemplateAttributeLine::create([
    //                 'product_tmpl_id' => $template->id,
    //                 'attribute_id' => $attribute['attribute_id'],
    //                 'active' => '1'
    //             ]);
    //             $hiddenLine = ProductAttributeProductTemplateRels::create([
    //                 'product_attribute_id' => $attribute['attribute_id'],
    //                 'product_template_id' => $template->id
    //             ]);
    //             $values = $attribute['value'];
    //             if (!empty($values)) {
    //                 foreach ($values as $value) {
    //                     $att_value = ProductAttributeValue::find($value);
    //                     // dd($att_value->name['en']);
    //                     $valueLine = ProductTemplateAttributeValue::create([
    //                         'p_a_value_id' => $value,
    //                         'a_l_id' => $line->id,
    //                         'product_tmpl_id' => $template->id,
    //                         'attribute_id' => $attribute['attribute_id'],
    //                         'value_name' => $att_value->name['en'],
    //                         'price_extra' => 0

    //                     ]);

    //                     //te5bis


    //                     $valueLineHidden = ProductAttributeValueProductTemplateAttributeLineRels::create([
    //                         'product_attribute_value_id' => $value,
    //                         'product_template_attribute_line_id' => $line->id
    //                     ]);
    //                 }
    //             }
    //         }
    //         $templateProducts = $this->generateCombinations($attributes);
    //         $arrayss = $this->sliceArrayElements($templateProducts);
    //         dd($arrayss);

    //         foreach ($templateProducts as $array) {
    //             $name = '';
    //             $extra = 0;
    //             $related_att = '';
    //             $lines = '';
    //             // foreach ($arrays as $array) {
    //             $value = ProductAttributeValue::find($array);
    //             $name = $name . ($value->name['en']) . ",";


    //             $price_line = ProductTemplateAttributeValue::where('p_a_value_id', $array)->where('product_tmpl_id', $template->id)->first();
    //             // dd($price_line);
    //             $extra += $price_line->price_extra;
    //             $related_att = $related_att . ($array) . ',';
    //             $lines = $lines . ($price_line->id) . ',';
    //             // };
    //             $name = substr($name, 0, -1);
    //             $lines = substr($lines, 0, -1);
    //             $related_att = substr($related_att, 0, -1);
    //             $product = ProductProduct::create([

    //                 'product_tmpl_id' => $template->id,
    //                 'categ_id' => $template->categ_id,
    //                 'name' => [
    //                     'en' => $template->name['en'] . ':' . $name,
    //                     'ar' => 'Null',
    //                 ],
    //                 'drinks_caption' => $template->drinks_caption,
    //                 'sides_caption' => $template->sides_caption,
    //                 'related_caption' => $template->related_caption,
    //                 'liked_caption' => $template->liked_caption,
    //                 'desserts_caption' => $template->desserts_caption,
    //                 'lst_price' => $template->list_price + $extra,
    //                 'related_att_values' => $related_att,
    //                 'template_name' => $template->name['en'],
    //                 'variant_name' => $name,
    //                 'p_t_a_v_line' => $lines,
    //                 'image' => $template->image

    //             ]);
    //         }
    //     }
    // }
    protected function afterCreate(): void
    {
        $template = $this->record;
        $attributes = $this->data['attributes'];
        if (empty($attributes)) {
            // dd($template->image);
            $product = ProductProduct::create([
                'product_tmpl_id' => $template->id,
                'default' => true,
                'categ_id' => $template->categ_id,
                'name' => [
                    'en' => $template->name['en'],
                    'ar' => $template->name['ar'],
                ],
                'description' => [
                    'en' => $template->description['en'],
                    'ar' => $template->description['ar'],
                ],

                'drinks_caption' => $template->drinks_caption,
                'sides_caption' => $template->sides_caption,
                'related_caption' => $template->related_caption,
                'liked_caption' => $template->liked_caption,
                'desserts_caption' => $template->desserts_caption,
                'lst_price' => $template->list_price,
                'related_att_values' => "null",
                'template_name' => $template->name['en'],
                'variant_name' => null,
                'p_t_a_v_line' => null,
                'image' => $template->image,
                'tax_included' => $template->tax_included,
                'kitchen_id' => $template->kitchen_id,
                'company_id' => $template->company_id,
                //                'description' => $template->description['en'],
                'preparing_time' => $template->preparing_time,
                'is_add_ons' => $template->is_add_ons,
                'is_ingredient' => $template->is_ingredient,
                'is_delivery' => $template->is_delivery,
                'is_combo' => $template->is_combo,
                'created_at' => $template->created_at,
                'updated_at' => $template->updated_at,
            ]);
        } else {

            foreach ($attributes as $attribute) {
                $line = ProductTemplateAttributeLine::create([
                    'product_tmpl_id' => $template->id,
                    'attribute_id' => $attribute['attribute_id'],
                    'active' => '1'
                ]);
                $hiddenLine = ProductAttributeProductTemplateRels::create([
                    'product_attribute_id' => $attribute['attribute_id'],
                    'product_template_id' => $template->id
                ]);
                $values = $attribute['value'];
                if (!empty($values)) {
                    foreach ($values as $value) {
                        $att_value = ProductAttributeValue::find($value);
                        // dd($att_value->name['en']);
                        $valueLine = ProductTemplateAttributeValue::create([
                            'p_a_value_id' => $value,
                            'a_l_id' => $line->id,
                            'product_tmpl_id' => $template->id,
                            'attribute_id' => $attribute['attribute_id'],
                            'value_name' => $att_value->name['en'],
                            'price_extra' => 0

                        ]);

                        //te5bis


                        $valueLineHidden = ProductAttributeValueProductTemplateAttributeLineRels::create([
                            'product_attribute_value_id' => $value,
                            'product_template_attribute_line_id' => $line->id
                        ]);
                    }
                }
            }
            $templateProducts = $this->generateCombinations($attributes);
            $arrayss = $this->sliceArrayElements($templateProducts);
            // dd($arrayss);
            foreach ($arrayss as $arrays) {
                $name_en = '';
                $name_ar = '';
                $extra = 0;
                $related_att = '';
                $lines = '';
                foreach ($arrays as $array) {
                    $value = ProductAttributeValue::find($array);
                    $name_en = $name_en . ($value->name['en']) . ",";
                    $name_ar = $name_ar . ($value->name['ar']) . ",";


                    $price_line = ProductTemplateAttributeValue::where('p_a_value_id', $array)->where('product_tmpl_id', $template->id)->first();
                    // dd($price_line);
                    $extra += $price_line->price_extra;
                    $related_att = $related_att . ($array) . ',';
                    $lines = $lines . ($price_line->id) . ',';
                };
                $name_en = substr($name_en, 0, -1);
                $name_ar = substr($name_ar, 0, -1);
                $lines = substr($lines, 0, -1);
                $related_att = substr($related_att, 0, -1);
                $product = ProductProduct::create([

                    'product_tmpl_id' => $template->id,
                    'categ_id' => $template->categ_id,
                    'default' => count($arrayss) == 1 ? true : false,
                    'name' => [
                        'en' => $template->name['en'] . ' (' . $name_en . ')',
                        'ar' => $template->name['ar'] . ' (' . $name_ar . ')',
                    ],
                    'drinks_caption' => $template->drinks_caption,
                    'sides_caption' => $template->sides_caption,
                    'related_caption' => $template->related_caption,
                    'liked_caption' => $template->liked_caption,
                    'desserts_caption' => $template->desserts_caption,
                    'lst_price' => $template->list_price + $extra,
                    'related_att_values' => $related_att,
                    'template_name' => $template->name['en'],
                    'variant_name' => $name_en,
                    'p_t_a_v_line' => $lines,
                    'image' => $template->image,
                    'tax_included' => $template->tax_included,
                    'kitchen_id' => $template->kitchen_id,
                    'company_id' => $template->company_id,
                    'description' => $template->description,
                    'preparing_time' => $template->preparing_time,
                    'is_add_ons' => $template->is_add_ons,
                    'is_ingredient' => $template->is_ingredient,
                    'is_delivery' => $template->is_delivery,
                    'is_combo' => $template->is_combo,
                    'created_at' => $template->created_at,
                    'updated_at' => $template->updated_at,
                ]);
                $products = ProductProduct::where('product_tmpl_id', $template->id)->where('active', 1)->get();
                $default_activation = 0;
                foreach ($products as $item) {
                    if ($item->default) {
                        $default_activation++;
                    }
                }
                if ($default_activation == 0) {
                    $product->default = true;
                    $product->save();
                }
            }
        }
    }
    function generateCombinations($attributes, $currentIndex = 0, $currentCombination = [])
    {
        $attributeKeys = array_keys($attributes);

        if ($currentIndex == count($attributeKeys)) {
            return [implode('-', $currentCombination)];
        }

        $currentKey = $attributeKeys[$currentIndex];
        $currentAttribute = $attributes[$currentKey];
        $values = $currentAttribute['value'];
        $combinations = [];

        foreach ($values as $value) {
            $newCombination = $currentCombination;
            $newCombination[] = $value;

            $combinations = array_merge(
                $combinations,
                $this->generateCombinations($attributes, $currentIndex + 1, $newCombination)
            );
        }

        return $combinations;
    }
    function sliceArrayElements($inputArray)
    {
        $slicedArrays = [];

        foreach ($inputArray as $element) {
            $slices = explode('-', $element); // Split the element into individual characters
            $slicedArrays[] = $slices;
        }

        return $slicedArrays;
    }

    function checkIngredients()
    {
        $removable = $this->data['removable_id'];
        $ingredients = $this->data['ingredients_id'];
        if (!empty($removable) && !empty(array_diff($removable, $ingredients))) {

            Notification::make()
                ->warning()
                ->title('Removable is NOT and ingredient!')
                ->body('Removable ingredients must be an ingredient first.')
                ->persistent()
                ->send();

            $this->halt();
        }
    }
    public function getTitle(): string
    {
        return __('Create Product');
    }
}
