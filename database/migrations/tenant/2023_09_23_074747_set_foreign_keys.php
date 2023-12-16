<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_templates', function (Blueprint $table) {
            // $table->foreign('base_unit_id')->references('id')->on('website_base_units')->onDelete('set null');
            $table->foreign('categ_id')->references('id')->on('product_categories');
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
            $table->foreign('default_drink_id')->references('id')->on('product_products')->onDelete('set null');
            $table->foreign('default_sides_id')->references('id')->on('product_products')->onDelete('set null');
            $table->foreign('kitchen_id')->references('id')->on('digitile_kitchens');
            // $table->foreign('location_shelf')->references('id')->on('stock_locations')->onDelete('set null');
            // $table->foreign('pos_categ_id')->references('id')->on('pos_categories')->onDelete('set null');
            // $table->foreign('uom_id')->references('id')->on('uom_uoms');
            // $table->foreign('uom_po_id')->references('id')->on('uom_uoms');
            // $table->foreign('website_id')->references('id')->on('websites');
            // $table->foreign('website_ribbon_id')->references('id')->on('product_ribbons')->onDelete('set null');
        });
        Schema::table('cat_product_related_rels', function (Blueprint $table) {
            $table->foreign('product_product_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_template_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('digitile_kitchens', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');

        });
        Schema::table('desserts_product_related_rels', function (Blueprint $table) {
            $table->foreign('product_product_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_template_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('product_template_attribute_lines', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('product_attributes');
            $table->foreign('product_tmpl_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('product_template_attribute_values', function (Blueprint $table) {
            $table->foreign('p_a_value_id')->references('id')->on('product_attribute_values')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('a_l_id')->references('id')->on('product_template_attribute_lines')->onDelete('cascade');
            $table->foreign('product_tmpl_id')->references('id')->on('product_templates')->onDelete('set null');
        });
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
        });
        Schema::table('drinks_product_related_rels', function (Blueprint $table) {
            $table->foreign('product_product_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_template_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('latitude_longitudes', function (Blueprint $table) {
            $table->foreign('zone_id')->references('id')->on('zone_zones')->onDelete('set null');
        });
        Schema::table('liked_product_related_rels', function (Blueprint $table) {
            $table->foreign('product_product_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_template_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('product_template_attribute_value_sale_order_line_rels', function (Blueprint $table) {
            $table->foreign('p_t_a_value_id', 'fk_p_t_a_value_id')->references('id')->on('product_template_attribute_values');
            $table->foreign('s_o_l_id', 'fk_s_o_l_id')->references('id')->on('sale_order_lines')->onDelete('cascade');
        });
        Schema::table('product_attribute_value_product_template_attribute_line_rels', function (Blueprint $table) {
            $table->foreign('product_template_attribute_line_id', 'p_t_a_l_id')->references('id')->on('product_template_attribute_lines')->onDelete('cascade');
            $table->foreign('product_attribute_value_id', 'p_a_v_id')->references('id')->on('product_attribute_values')->onDelete('cascade');
        });
        Schema::table('product_variant_combinations', function (Blueprint $table) {
            $table->foreign('p_p_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('p_t_a_value_id')->references('id')->on('product_template_attribute_values');
        });
        Schema::table('product_wishlists', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('res_partners')->onDelete('set null');
            $table->foreign('pricelist_id')->references('id')->on('product_pricelists')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('product_products');
            $table->foreign('product_template_id')->references('id')->on('product_templates')->onDelete('set null');
            // $table->foreign('website_id')->references('id')->on('website')->onDelete('cascade');
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('cascade');
            // $table->foreign('removal_strategy_id')->references('id')->on('product_removal')->onDelete('set null');
        });
        Schema::table('res_company_users_rels', function (Blueprint $table) {
            $table->foreign('cid')->references('id')->on('res_companies')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('product_category_product_product_rels', function (Blueprint $table) {

            $table->foreign('product_category_id', 'p_c_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('product_product_id', 'p_p_id')->references('id')->on('product_products')->onDelete('cascade');
        });
        Schema::table('orders_trips', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('res_partners');
            // $table->foreign('vehicle_id')->references('id')->on('fleet_vehicle');
        });
        Schema::table('product_addons_rels', function (Blueprint $table) {
            $table->foreign('addons_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_templates')->onDelete('cascade');
        });

        Schema::table('res_groups', function (Blueprint $table) {
            //   $table->foreign('category_id')->references('id')->on('ir_module_category')->onDelete('set null');
        });
        Schema::table('product_attribute_product_template_rels', function (Blueprint $table) {
            $table->foreign('product_attribute_id', 'p_a_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('product_template_id', 'p_t_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('product_ingredient_rels', function (Blueprint $table) {
            $table->foreign('ingredient_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('res_groups_users_rels', function (Blueprint $table) {
            $table->foreign('gid')->references('id')->on('res_groups')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('main_banner1s', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('cascade');
        });
        Schema::table('main_banner2s', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('cascade');
        });
        Schema::table('main_banner3s', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('cascade');
        });
        Schema::table('main_page_sections', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('cascade');
        });
        Schema::table('product_main_page_section_rels', function (Blueprint $table) {
            $table->foreign('main_page_section_id')->references('id')->on('main_page_sections')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_products')->onDelete('cascade');
        });
        Schema::table('product_pricelists', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('res_currencies');
            // $table->foreign('website_id')->references('id')->on('website');
        });
        Schema::table('res_partners', function (Blueprint $table) {
            // $table->foreign('city_id')->references('id')->on('state_city')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
            // $table->foreign('country_id')->references('id')->on('res_country');
            // $table->foreign('country_of_birth')->references('id')->on('res_country')->onDelete('set null');
            // $table->foreign('industry_id')->references('id')->on('res_partner_industry')->onDelete('set null');
            // $table->foreign('latest_followup_level_id_without_lit')->references('id')->on('followup_line')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('res_partners')->onDelete('set null');
            // $table->foreign('state_id')->references('id')->on('res_country_state');
            // $table->foreign('street_id')->references('id')->on('city_street')->onDelete('set null');
            // $table->foreign('team_id')->references('id')->on('crm_team')->onDelete('set null');
            // $table->foreign('title')->references('id')->on('res_partner_title')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('website_id')->references('id')->on('website');
            $table->foreign('kitchen_id')->references('id')->on('digitile_kitchens')->onDelete('set null');
        });
        Schema::table('product_pricelist_items', function (Blueprint $table) {
            $table->foreign('base_pricelist_id')->references('id')->on('product_pricelists')->onDelete('set null');
            $table->foreign('categ_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('res_currencies')->onDelete('set null');
            $table->foreign('pricelist_id')->references('id')->on('product_pricelists')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('product_tmpl_id')->references('id')->on('product_templates')->onDelete('cascade');
        });
        Schema::table('res_users', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies');
            $table->foreign('partner_id')->references('id')->on('res_partners');
            // $table->foreign('sale_team_id')->references('id')->on('crm_team')->onDelete('set null');
            // $table->foreign('user_platform')->references('id')->on('mobile_platform')->onDelete('set null');
            // $table->foreign('website_id')->references('id')->on('website')->onDelete('set null');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies');
            $table->foreign('partner_id')->references('id')->on('res_partners');
        });
        Schema::table('sale_orders', function (Blueprint $table) {
            // $table->foreign('analytic_account_id')->references('id')->on('account_analytic_account')->onDelete('set null');
            // $table->foreign('campaign_id')->references('id')->on('utm_campaign')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('res_currencies');
            // $table->foreign('fiscal_position_id')->references('id')->on('account_fiscal_position')->onDelete('set null');
            // $table->foreign('incoterm')->references('id')->on('account_incoterms')->onDelete('set null');
            // $table->foreign('medium_id')->references('id')->on('utm_medium')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onDelete('set null');
            $table->foreign('partner_id')->references('id')->on('res_partners');
            $table->foreign('driver_id')->references('id')->on('res_partners');
            $table->foreign('partner_invoice_id')->references('id')->on('res_partners');
            $table->foreign('partner_shipping_id')->references('id')->on('res_partners');
            // $table->foreign('payment_term_id')->references('id')->on('account_payment_term')->onDelete('set null');
            $table->foreign('pricelist_id')->references('id')->on('product_pricelists');
            // $table->foreign('procurement_group_id')->references('id')->on('procurement_groups')->onDelete('set null');
            // $table->foreign('project_id')->references('id')->on('project_projects')->onDelete('set null');
            // $table->foreign('sale_order_template_id')->references('id')->on('sale_order_templates')->onDelete('set null');
            $table->foreign('sale_order_type_id')->references('id')->on('sale_order_types');
            // $table->foreign('source_id')->references('id')->on('utm_source')->onDelete('set null');
            // $table->foreign('team_id')->references('id')->on('crm_team')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('warehouse_id')->references('id')->on('stock_warehouses');
            // $table->foreign('website_id')->references('id')->on('website')->onDelete('set null');
        });
        Schema::table('product_products', function (Blueprint $table) {
            // $table->foreign('base_unit_id')->references('id')->on('website_base_unit')->onDelete('set null');
            // $table->foreign('create_uid')->references('id')->on('res_users')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onDelete('set null');
            $table->foreign('product_tmpl_id')->references('id')->on('product_templates')->onDelete('cascade');
            // $table->foreign('product_default_sides_id')->references('id')->on('product_products')->onDelete('cascade');
            // $table->foreign('product_default_drink_id')->references('id')->on('product_products')->onDelete('cascade');
            $table->foreign('categ_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('cascade');
            $table->foreign('kitchen_id')->references('id')->on('digitile_kitchens')->onDelete('cascade');
            // $table->foreign('default_drink_id')->references('id')->on('product_templates')->onDelete('cascade');
            // $table->foreign('default_sides_id')->references('id')->on('product_templates')->onDelete('cascade');


        });
        Schema::table('sale_order_lines', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('sale_orders')->onDelete('cascade');
            // $table->foreign('company_id')->references('id')->on('res_company')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('res_currencies')->onDelete('set null');
            $table->foreign('order_partner_id')->references('id')->on('res_partners')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('product_products');
            // $table->foreign('product_packaging_id')->references('id')->on('product_packaging')->onDelete('set null');
            // $table->foreign('product_uom')->references('id')->on('uom_uom');


            // $table->foreign('project_id')->references('id')->on('project_projects')->onDelete('set null');
            // $table->foreign('route_id')->references('id')->on('stock_route');
        });
        // Schema::table('sale_order_types', function (Blueprint $table) {
        //     $table->foreign('order_id')->references('id')->on('sale_orders')->onDelete('cascade');
        //     // $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
        //     $table->foreign('currency_id')->references('id')->on('res_currencies')->onDelete('set null');
        //     $table->foreign('order_partner_id')->references('id')->on('res_partners')->onDelete('set null');
        //     $table->foreign('salesman_id')->references('id')->on('users')->onDelete('set null');
        //     // $table->foreign('product_packaging_id')->references('id')->on('product_packaging')->onDelete('set null');
        //     // $table->foreign('product_uom')->references('id')->on('uom_uoms');
        //     // $table->foreign('project_id')->references('id')->on('project_projects')->onDelete('set null');
        //     // $table->foreign('route_id')->references('id')->on('stock_routes');
        //     $table->foreign('linked_line_id')->references('id')->on('sale_order_lines')->onDelete('cascade');
        // });

        Schema::table('zone_zones', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('res_companies');
            // $table->foreign('warehouse_id')->references('id')->on('stock_warehouses')->onDelete('set null');
        });

        Schema::table('product_removable_ingredient_rels', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product_templates')->onDelete('cascade');
            $table->foreign('removable_ingredient_id', 'prir_ri')->references('id')->on('product_products')->onDelete('cascade');
        });
        Schema::table('product_tags', function (Blueprint $table) {
            // $table->foreign('ribbon_id')->references('id')->on('product_ribbon')->onDelete('set null');
            // $table->foreign('website_id')->references('id')->on('website');
        });
        Schema::table('product_tag_product_product_rels', function (Blueprint $table) {
            $table->foreign('product_product_id')
                ->references('id')
                ->on('product_products')
                ->onDelete('cascade');

            $table->foreign('product_tag_id')
                ->references('id')
                ->on('product_tags')
                ->onDelete('cascade');
        });
        Schema::table('product_tag_product_template_rels', function (Blueprint $table) {
            $table->foreign('product_template_id')
                ->references('id')
                ->on('product_templates')
                ->onDelete('cascade');

            $table->foreign('product_tag_id')
                ->references('id')
                ->on('product_tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
