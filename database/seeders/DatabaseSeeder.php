<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('res_partners')->insert([
            'name' => 'partner1',
            'partner_latitude' => '33.885968368174',
            'partner_longitude' => '35.480931140241'
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'login' => '11223344',
            'partner_id' => '1',
            'password' => Hash::make('password'),
        ]);

        DB::table('res_currencies')->insert([
            'name' =>  json_encode(['en' => 'currency1']),
            'symbol' =>  json_encode(['en' => 'curr1']),
        ]);

        DB::table('res_companies')->insert([
            'name' => "DIGITILE",
            'partner_id' => '1',
            'currency_id' => '1',
            'is_main' => '0',
            'tax' => '0'
        ]);

        DB::table('users')->update([
            'company_id' => '1',
        ]);

        DB::table('res_partners')->update([
            'user_id' => '1',
            'company_id' => '1',
        ]);
        DB::table('product_pricelists')->insert([
            'currency_id' => '1',
            'discount_policy' => '20%',
            'name' => json_encode(['en' => 'productPricelist1']),
        ]);
        DB::table('res_groups')->insert([
            'name' => json_encode(['en' => 'group 1', 'ar' => 'مجموعة 1']),
        ]);

        DB::table('sale_order_types')->insert([
            'name' => 'Delivery'
        ]);
        DB::table('sale_order_types')->insert([
            'name' => 'Pickup'
        ]);
        DB::table('sale_order_types')->insert([
            'name' => 'Event'
        ]);
        DB::table('digitile_kitchens')->insert([
            'name' => 'kitchen1',
            'company_id' => '1',
            'is_default' => 0
        ]);
        DB::table('product_categories')->insert([
            'name' => json_encode(['en' => 'category1']),
            'company_id' => 1
        ]);
        DB::table('product_templates')->insert([
            'categ_id' => '1',
            'name' => json_encode(['en' => 'template1']),
            'sequence' => '123',
            'drinks_caption' => json_encode(['en' => 'drinksCaption', 'ar' => 'ar']),
            'sides_caption' => json_encode(['en' => 'sideCaption', 'ar' => 'ar']),
            'related_caption' => json_encode(['en' => 'related_caption', 'ar' => 'ar']),
            'liked_caption' => json_encode(['en' => 'liked_caption', 'ar' => 'ar']),
            'desserts_caption' => json_encode(['en' => 'desserts_caption', 'ar' => 'ar']),
            'company_id' => '1'
        ]);
        DB::table('product_products')->insert([
            'product_tmpl_id' => '1',
            'categ_id' => '1',
            'barcode' => '1',
            'name' => json_encode(['en' => 'product1']),
            'default_code' => 's123',
            'description' => json_encode(['en' => 'description1']),
            'drinks_caption' => json_encode(['en' => 'drinksCaption']),
            'sides_caption' => json_encode(['en' => 'sideCaption']),
            'related_caption' => json_encode(['en' => 'related_caption']),
            'liked_caption' => json_encode(['en' => 'liked_caption']),
            'desserts_caption' => json_encode(['en' => 'desserts_caption']),
            'template_name' => 'template1',
            'company_id' => '1'

        ]);
        //create is delivery product
        DB::table('product_templates')->insert([
            'categ_id' => '1',
            'name' => json_encode(['en' => 'delivery fees', 'ar' => 'كلفة التوصيل']),
            'sequence' => '123',
            'is_delivery' => true,
            'company_id' => '1'

        ]);
        DB::table('product_products')->insert([
            'product_tmpl_id' => '2',
            'categ_id' => '1',
            'barcode' => '1',
            'name' => json_encode(['en' => 'delivery fees', 'ar' => 'كلفة التوصيل']),
            'default_code' => 's123',
            'template_name' => 'delivery',
            'is_delivery' => 1,
            'company_id' => '1'
        ]);
    }
}
