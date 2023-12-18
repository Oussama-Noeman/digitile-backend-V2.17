<?php

namespace App\Filament\Resources\ProductTemplateResource\Pages;

use App\Filament\Resources\ProductTemplateResource;
use App\Models\Tenant\ProductAttributeProductTemplateRels;
use App\Models\Tenant\ProductAttributeValue;
use App\Models\Tenant\ProductAttributeValueProductTemplateAttributeLineRels;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductTemplateAttributeLine;
use App\Models\Tenant\ProductTemplateAttributeValue;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditProductTemplate extends EditRecord
{
    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->warning()
            ->send();
    }

    protected static string $resource = ProductTemplateResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    protected function beforeSave(): void
    {
        $template = $this->record;
        $attributes = $this->data['oldAttributes'];
        $template_name = $this->data['name'];


        foreach ($attributes as $attribute) {
            $values[] = $attribute['attribute_id'];
        }
        if (!empty($values)) {
            if ($this->hasDuplicate($values)) {
                Notification::make()
                    ->warning()
                    ->title('Duplicated Attributes!')
                    ->body('You cannot add two same attributes')
                    ->persistent()
                    ->send();
                $this->halt();
            }
        }
        $this->checkRemovable();

        //tjrib
        //START ATTRIBUTES-VALUES OF OLD-NEW REPEATER COMPARISON
        $old_repeater_values = ProductTemplateAttributeValue::where('product_tmpl_id', $template->id)->get();
        $grouped_values = $old_repeater_values->groupBy('attribute_id')->map(function ($group) {
            return [
                "attribute_id" => $group[0]['attribute_id'],
                "values" => $group->pluck('p_a_value_id')->map(function ($value) {
                    return (string) $value;
                })->toArray(),
            ];
        })->values();
        $old_repeater_values_array = $grouped_values->toArray();
        $grouped_values = collect($attributes)->groupBy('attribute_id')->map(function ($group) {
            $attributeId = $group[0]['attribute_id'];
            $values = $group->pluck('values')->collapse()->toArray();

            return [
                "attribute_id" => $attributeId,
                "values" => $values,
            ];
        })->values();

        $new_repeater_values_array = $grouped_values->toArray();
        //END ATTRIBUTES-VALUES OF OLD-NEW REPEATER COMPARISON


        //START TEST OF REPEATER VALUES COUNTER
        $values = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '1')->get()->pluck('related_att_values')->toArray();
        $uniqueValues = [];
        foreach ($values as $value) {
            $valueArray = explode(',', $value);
            $valueArray = array_filter($valueArray);
            $uniqueValues = array_merge($uniqueValues, array_unique($valueArray));
        }
        $uniqueValues = array_values(array_unique($uniqueValues));
        $old_counter = count(array_unique($uniqueValues));

        $new_counter = 0;

        foreach ($new_repeater_values_array as $item) {
            if (isset($item['values']) && is_array($item['values'])) {
                $new_counter += count($item['values']);
            }
        }
        //END TEST OF REPEATER VALUES COUNTER
        if (in_array("null", $uniqueValues)) {
            $old_counter = 0;
        } else {
            $old_counter = count($uniqueValues);
        }
        //tjrib end

        $products = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '1')->get();
        foreach ($products as $product) {
            foreach (['en', 'ar'] as $language) {
                $textAfterColon = substr($product->name[$language], strpos($product->name[$language], ':') + 1);

                $templateString = $template_name[$language];

                $newString = $templateString . ':' . $textAfterColon;

                $modifiedNameArray[$language] = mb_convert_encoding($newString, 'UTF-8');
            }

            $product->name = $modifiedNameArray;

            // dd($product->name);
            if ($this->compareArrays($old_repeater_values_array, $new_repeater_values_array) || $old_counter != $new_counter) {
                $product->save();
                return;
            } else {
                $product->kitchen_id = $this->data['kitchen_id'];
                $product->company_id = $this->data['company_id'];
                $product->description = $this->data['description'];
                $product->preparing_time = $this->data['preparing_time'];
                $product->is_add_ons = $this->data['is_add_ons'];
                $product->is_ingredient = $this->data['is_ingredient'];
                $product->is_delivery = $this->data['is_delivery'];
                $product->is_combo = $this->data['is_combo'];
                $product->updated_at = $this->data['updated_at'];
                $product->lst_price -= $template->list_price;
                $product->lst_price += $this->data['list_price'];

                $product->save();
            }
        }
    }
    function hasDuplicate($array)
    {
        $elementCount = [];

        foreach ($array as $element) {
            if (isset($elementCount[$element])) {
                return true;
            }
            $elementCount[$element] = true;
        }
        return false;
    }

    public function checkRemovable()
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
    protected function afterSave(): void
    {
        //START DEFINE VARIABLES
        $template_name = $this->data['name'];
        $template = $this->record;
        $attributes = $this->data['oldAttributes'];
        // dd($template->name['ar']);
        $first_product = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '1')->first();
        if ($first_product->name == $template->name['en'] && empty($attributes)) {
            $first_product->lst_price = $template->list_price;
            $first_product->save();
            // dd($template->name['en']);
        } else {

            //START ATTRIBUTES-VALUES OF OLD-NEW REPEATER COMPARISON
            $old_repeater_values = ProductTemplateAttributeValue::where('product_tmpl_id', $template->id)->get();
            $grouped_values = $old_repeater_values->groupBy('attribute_id')->map(function ($group) {
                return [
                    "attribute_id" => $group[0]['attribute_id'],
                    "values" => $group->pluck('p_a_value_id')->map(function ($value) {
                        return (string) $value;
                    })->toArray(),
                ];
            })->values();
            $old_repeater_values_array = $grouped_values->toArray();
            $grouped_values = collect($attributes)->groupBy('attribute_id')->map(function ($group) {
                $attributeId = $group[0]['attribute_id'];
                $values = $group->pluck('values')->collapse()->toArray();

                return [
                    "attribute_id" => $attributeId,
                    "values" => $values,
                ];
            })->values();

            $new_repeater_values_array = $grouped_values->toArray();
            //END ATTRIBUTES-VALUES OF OLD-NEW REPEATER COMPARISON


            //START TEST OF REPEATER VALUES COUNTER
            $values = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '1')->get()->pluck('related_att_values')->toArray();
            $uniqueValues = [];
            foreach ($values as $value) {
                $valueArray = explode(',', $value);
                $valueArray = array_filter($valueArray);
                $uniqueValues = array_merge($uniqueValues, array_unique($valueArray));
            }
            $uniqueValues = array_values(array_unique($uniqueValues));
            $old_counter = count(array_unique($uniqueValues));

            $new_counter = 0;

            foreach ($new_repeater_values_array as $item) {
                if (isset($item['values']) && is_array($item['values'])) {
                    $new_counter += count($item['values']);
                }
            }
            //END TEST OF REPEATER VALUES COUNTER
            if (in_array("null", $uniqueValues)) {
                $old_counter = 0;
            } else {
                $old_counter = count($uniqueValues);
            }


            //START LOGIC IF ANY EDIT OF REPEATER OCCURRED
            if ($this->compareArrays($old_repeater_values_array, $new_repeater_values_array) || $old_counter != $new_counter) {
                $old_attr_values = ProductTemplateAttributeValue::where('product_tmpl_id', $template->id)->delete();
                $old_hidden_line = ProductAttributeProductTemplateRels::where('product_template_id', $template->id)->delete();
                $old_lines = ProductTemplateAttributeLine::where('product_tmpl_id', $template->id)->delete();

                $old_products = ProductProduct::where('product_tmpl_id', $template->id)->get();
                foreach ($old_products as $old_product) {
                    $old_product->active = 0;
                    $old_product->save();
                }
                // dd($template->name['en']);
                if (!empty($attributes)) {
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
                        $values = $attribute['values'];
                        foreach ($values as $value) {
                            $att_value = ProductAttributeValue::find($value);
                            $valueLine = ProductTemplateAttributeValue::create([
                                'p_a_value_id' => $value,
                                'a_l_id' => $line->id,
                                'product_tmpl_id' => $template->id,
                                'attribute_id' => $attribute['attribute_id'],
                                'value_name' => $att_value->name['en'],
                                'price_extra' => 0

                            ]);
                            $valueLineHidden = ProductAttributeValueProductTemplateAttributeLineRels::create([
                                'product_attribute_value_id' => $value,
                                'product_template_attribute_line_id' => $line->id
                            ]);
                        }
                    }
                    $templateProducts = $this->generateCombinations($attributes);
                    $arrayss = $this->sliceArrayElements($templateProducts);
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
                            $extra += $price_line->price_extra;
                            $related_att = $related_att . ($array) . ',';
                            $lines = $lines . ($price_line->id) . ',';
                        };
                        $name_en = substr($name_en, 0, -1);
                        $name_ar = substr($name_ar, 0, -1);
                        $lines = substr($lines, 0, -1);
                        $related_att = substr($related_att, 0, -1);
                        // dd($template->name['en']);
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
                } else {
                    // dd($template->name['en']);
                    ProductProduct::create([
                        'product_tmpl_id' => $template->id,
                        'categ_id' => $template->categ_id,
                        'default' => true,
                        'name' => [
                            'en' => $template->name['en'],
                            'ar' => $template->name['ar'],
                        ],
                        'drinks_caption' => $template->drinks_caption,
                        'sides_caption' => $template->sides_caption,
                        'related_caption' => $template->related_caption,
                        'liked_caption' => $template->liked_caption,
                        'desserts_caption' => $template->desserts_caption,
                        'lst_price' => $template->list_price,
                        'related_att_values' => "null",
                        'template_name' => $template->name['en'],
                        'variant_name' => "null",
                        'p_t_a_v_line' => "null",
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
                }

                // $products = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '0')->get();
                // foreach ($products as $product) {
                //     $product->list_price += $template->list_price;
                //     $product->save();
                // }
                // } else {
                //     $products = ProductProduct::where('product_tmpl_id', $template->id)->where('active', '1')->get();
                //     foreach ($products as $product) {
                //         foreach (['en', 'ar'] as $language) {
                //             $textAfterColon = substr($product->name[$language], strpos($product->name[$language], ':') + 1);

                //             $templateString = $template_name[$language];

                //             $newString = $templateString . ':' . $textAfterColon;

                //             $modifiedNameArray[$language] = $newString;
                //         }

                //         $product->name = $modifiedNameArray;

                //         // dd($product->name);
                //         $product->lst_price -= $template->list_price;
                //         $product->lst_price += $this->data['list_price'];
                //         dd($product->lst_price);
                //         $product->save();
                //     }
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
        $values = $currentAttribute['values'];

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
            $slices = explode('-', $element);
            $slicedArrays[] = $slices;
        }

        return $slicedArrays;
    }

    function compareArrays($array1, $array2)
    {
        $combined = [];

        foreach ($array1 as $item) {
            $combined[$item['attribute_id']][] = $item['values'];
        }
        foreach ($array2 as $item) {
            $combined[$item['attribute_id']][] = $item['values'];
        }
        foreach ($combined as $values) {
            if (count($values) !== 2 || $values[0] !== $values[1]) {
                return true;
            }
        }
        return false;
    }
    public function getTitle(): string
    {
        return __('Edit Product');
    }
}
