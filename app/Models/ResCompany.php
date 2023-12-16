<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResCompany extends Model
{
    use HasFactory;

    protected $table = 'res_companies';

    //  protected $casts = [

    //     "name" => "json",

    //  ];

    protected $fillable = [
        'name',
        'partner_id',
        'currency_id',
        'sequence',
        'create_date',
        'parent_id',
        'email',
        'phone',
        'mobile',
        'company_details',
        'active',
        'resource_calendar_id',
        'hr_presence_control_email_amount',
        'hr_presence_control_ip_list',
        'fees_type',
        'fixed_fees',
        'minimum_fees',
        'price_by_km',
        'social_twitter',
        'social_facebook',
        'social_github',
        'social_linkedin',
        'social_youtube',
        'social_instagram',
        'whatsapp',
        'is_main',
        'terms_and_conditions',
        'privacy_policy',
        'support',
        'category_image_attachment',
        'cart_image_attachment',
        'checkout_image_attachment',
        'deal_banner_image_attachment',
        'deal_background_image_attachment',
        'sign_banner_attachment',
        'category_title',
        'cart_title',
        'checkout_title',
        'deal_title1',
        'deal_title2',
        'tax',
        'tax_included',
        'image',
        'faq_banner',
        'career_banner',

        'has_pickup',
        'has_delivery',

        'delivery_time_type',
        'fixed_time'

    ];

    public function getCompanyNameAttribute()
    {
        return $this->company->name;
    }

    public function zoneZones()
    {
        return $this->hasMany(ZoneZone::class);
    }
    public function resPartner()
    {
        return $this->belongsTo(ResPartner::class, 'partner_id', 'id');
    }
    public function resPartner1()
    {
        return $this->hasMany(ResPartner::class, 'partner_id', 'id');
    }

    public function resCurrency()
    {
        return $this->belongsTo(ResCurrency::class, 'currency_id', 'id');
    }
    public function saleOrders()
    {
        return $this->hasMany(SaleOrder::class, 'company_id');
    }

    public function productPricelistItems()
    {
        return $this->hasMany(ProductPricelistItem::class, 'company_id', 'id');
    }

    public function productPricelists()
    {
        return $this->hasMany(ProductPricelist::class, 'company_id', 'id');
    }

    // public function companyUsersRel()
    // {
    //     return $this->hasMany(ResCompanyUsersRel::class, 'company_id', 'id');
    // }

    public function saleOrderLines()
    {
        return $this->hasMany(SaleOrderLine::class);
    }

    public function productTemplates()
    {
        return $this->hasMany(ProductTemplate::class, 'company_id');
    }
    // public function users()
    // {
    //     return $this->belongsToMany(User::class,'res_company_users_rels','cid','uid');
    // }
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function parent()
    {
        return $this->belongsTo(ResCompany::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(ResCompany::class, 'parent_id', 'id');
    }
    public function mainBanner1()
    {
        return $this->hasMany(MainBanner1::class, 'company_id');
    }
    public function mainBanner2()
    {
        return $this->hasMany(MainBanner1::class, 'company_id');
    }
    public function mainBanner3()
    {
        return $this->hasMany(MainBanner1::class, 'company_id');
    }
    public function mainPageSection()
    {
        return $this->hasMany(MainBanner1::class, 'company_id');
    }

    public function aboutUs()
    {
        return $this->hasMany(AboutUs::class, 'company_id');
    }
    public function aboutUsMissions()
    {
        return $this->hasMany(AboutUsMission::class, 'company_id');
    }
    public function aboutUsSliders()
    {
        return $this->hasMany(AboutUsSlider::class, 'company_id');
    }
    public function abouUsValues()
    {
        return $this->hasMany(AboutUsValue::class, 'company_id');
    }
    public function aboutUsVisions()
    {
        return $this->hasMany(AboutUsVision::class, 'company_id');
    }
    public function customerFeedbacks()
    {
        return $this->hasMany(CustomerFeedback::class, 'company_id');
    }
    public function teams()
    {
        return $this->hasMany(Team::class, 'company_id');
    }

    public function calendars()
    {
        return $this->hasMany(ResourceCalendar::class, 'company_id');
    }

    public function mailaingContacts()
    {
        return $this->hasMany(MailingContact::class, 'company_id');
    }
    public function contactUs()
    {
        return $this->hasMany(ContactUs::class, 'company_id');
    }
    public function hrJobs()
    {
        return $this->hasMany(HrJob::class, 'company_id');
    }

    public function careerInfo()
    {
        return $this->hasMany(CareerInformation::class);
    }
    public function faq()
    {
        return $this->hasMany(WebsiteFaq::class);
    }
}
