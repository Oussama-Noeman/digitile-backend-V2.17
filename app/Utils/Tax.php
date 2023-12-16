<?php

namespace App\Utils;

use App\Models\ProductProduct;
use App\Models\ProductTemplate;
use App\Models\ResCompany;

use Exception;

class Tax {
    
    public static function computeProductProductTax(ProductProduct $product,$price){
    
     if($product->tax_included){
        try {
             
             $tva=Tax::getProductProductTva($product);
           
            // $res = $productProduct->taxesId->computeAll($price, $productProduct);
            $price_product = round($price + ($tva * $price / 100),2);

        } catch (Exception $e) {
            $price_product = $price;
        }
    }
   else{
    $price_product = $price;
   }
   return $price_product;
    }
    public static function computeProductTemplateTax(ProductTemplate $product, $price){
        
         if($product->tax_included){
            try {
                 $tva=Tax::getProductTemplateTva($product);
                // $res = $productProduct->taxesId->computeAll($price, $productProduct);
                $price_product = round($price + ($tva * $price / 100),2);
    
            } catch (Exception $e) {
                $price_product = $price;
            }
        }
       else{
        $price_product = $price;
       }
       return $price_product;
        }
    public static function getProductProductTva(ProductProduct $product){
       
        $tva = 0;
        if($product->tax_included){
            try {
                
    
    
                if ($product->company) {
                   
                    $tva = $product->company->tax;
                } else {
    
                    $tva = ResCompany::first()->tax;
                }
            } catch (Exception $e) {
              return $tva;
            }
        }
       return $tva;
    }
    public static function getProductTemplateTva(ProductTemplate $product){
       
        $tva = 0;
        if($product->tax_included){
            try {
                
    
    
                if ($product->company) {
                   
                    $tva = $product->company->tax;
                } else {
    
                    $tva = ResCompany::first()->tax;
                }
            } catch (Exception $e) {
              return $tva;
            }
        }
       return $tva;
    }
}