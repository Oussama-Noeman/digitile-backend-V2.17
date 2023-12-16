<?php

namespace App\Filament\Resources\ProductTemplateResource\Pages;

use App\Filament\Resources\ProductTemplateResource;
use App\Models\ProductAttributeValue;
use App\Models\ProductTemplate;
use App\Models\ProductTemplateAttributeValue;
use Filament\Resources\Pages\Page;

class ConfigureProductTemplateAttributeValue extends Page
{
    protected static string $resource = ProductTemplateResource::class;

    public $record;

    public function mount(ProductTemplate $record)
    {
        $this->record = $record;
    }
    public function getViewData(): array
    {
        $ids = ProductTemplateAttributeValue::where('product_tmpl_id', $this->record->id)->get()->pluck('p_a_value_id');
        $values = ProductAttributeValue::whereIn('id', $ids)->get();
        return compact('values');
    }
    protected static string $view = 'filament.resources.product-template-resource.pages.configure-product-template-attribute-value';
}
