<?php

namespace App\Filament\Resources\ProductTemplateAttributeValueResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeValueResource;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductTemplateAttributeValue;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductTemplateAttributeValue extends EditRecord
{
    protected static string $resource = ProductTemplateAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    protected function getRedirectUrl(): string
    {
        $record = $this->record;
        return url("/admin/product-template-attribute-values?record={$record->product_tmpl_id}");
    }

    protected function afterSave(): void
    {
        $value = $this->record;
        $template = ProductTemplate::find($value->product_tmpl_id);
        $products = ProductProduct::where('p_t_a_v_line', 'LIKE', '%' . $value->id . '%')
            ->where('product_tmpl_id', $value->product_tmpl_id)
            ->where('active', '1')
            ->get();
        foreach ($products as $product) {
            $extra = 0;
            $lines = [];
            $p_t_a_v_line = $product->p_t_a_v_line;

            $numbers = explode(',', $p_t_a_v_line);
            $numbers = array_filter($numbers, 'strlen');
            $numbers = array_map('intval', $numbers);
            $lines = array_merge($lines, $numbers);

            foreach ($lines as  $line) {
                $ptavline = ProductTemplateAttributeValue::find($line);
                $extra += $ptavline->price_extra;
            }
            $product->lst_price = $template->list_price + $extra;
            $product->save();
        }
        // return $this->getResource()::getUrl('index');
    }
}
