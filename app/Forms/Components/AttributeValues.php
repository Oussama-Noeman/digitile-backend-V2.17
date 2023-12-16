<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class AttributeValues extends Field
{
    protected string $view = 'forms.components.attribute-values';
    public function getValues($record, $id)
    {
        $all_values = $record->templateAttributeValues->where('product_tmpl_id', $id);
        // $values = $all_values->filter(function ($value) use ($id) {
        //     return $value->product_tmpl_id = $id;
        // });
        return $all_values;
    }
}
