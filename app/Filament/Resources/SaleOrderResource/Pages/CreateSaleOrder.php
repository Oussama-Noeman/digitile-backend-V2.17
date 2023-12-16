<?php

namespace App\Filament\Resources\SaleOrderResource\Pages;

use App\Filament\Resources\SaleOrderResource;
use App\Models\DriverOrder;
use App\Models\ProductProduct;
use App\Models\ResPartner;
use App\Models\SaleOrder;
use App\Models\SaleOrderLine;
use App\Utils\Tax;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleOrder extends CreateRecord
{
    protected static string $resource = SaleOrderResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

   protected function afterCreate():void{
    $amount_untaxed=0;
    $total=0;
    $quantity= 0;
    $amount_tax=0;
    $saleorderLines=$this->data['saleOrderLines'];
   
    $order_id= $this->record->id;
    foreach ($saleorderLines as $item) {
      
        $total+=$item['price_total'];
        $quantity+=$item['product_uom_qty'];
        $amount_untaxed=$amount_untaxed+($item['product_uom_qty']*$item['price_unit']);
        $amount_tax=$amount_tax+$item['price_tax'];
        
 
    }
  
   SaleOrder::where('id', $order_id)->update([ 
    'amount_total'=> $total,
    'total_qty'=> $quantity,
    'amount_untaxed'=>$amount_untaxed,
    'amount_tax'=> $amount_tax,
   ]);
  
   
   }
   public function getTitle(): string 
   {
       return __('Create Sale Order');
   }

}
  