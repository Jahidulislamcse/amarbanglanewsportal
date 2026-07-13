<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $fillable = [
        'logo',
        'footer_logo',
        'favicon',
        'loader',
        'admin_loader',
        'title',
        'theme_color',
        'footer_color',
        'tawk_to',
        'is_talkto',
        'is_capcha',
        'captcha_site_key',
        'captcha_secret_key',
        'disqus',
        'is_disqus',
        'time_zone',
        'copyright_text',
        'copyright_color',
        'footer_text',
        'tags',
        'error_photo',
        'error_title',
        'error_text',
        'driver',
        'smtp_host',
        'smtp_port',
        'email_encryption',
        'smtp_user',
        'smtp_pass',
        'from_email',
        'from_name',
        'is_smtp',
        'is_verification_email',
		'home_category_section',
		'home_category_section_en',
		'payment_number',
        'version',
        'recent_news_limit',
        'popular_news_limit',
    ];
    protected $table    = 'generalsettings';
    public $timestamps  = false;
}
