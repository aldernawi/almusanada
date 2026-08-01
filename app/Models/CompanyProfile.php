<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'company_name',
        'hero_title',
        'hero_description',
        'about_text',
        'footer_text',
        'primary_color',
        'secondary_color',
        'contact_email',
        'whatsapp_number',
        'service_1_title',
        'service_1_description',
        'service_2_title',
        'service_2_description',
        'service_3_title',
        'service_3_description',
        'font_size',
    ];
}
