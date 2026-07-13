<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopReporter extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'total_views',
        'year',
        'month',
        'week'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function checkAndGenerateWeeklyBest()
    {
        $latest = self::whereNotNull('week')->orderBy('id', 'desc')->first();
        if (!$latest || \Carbon\Carbon::parse($latest->created_at)->addDays(7)->isPast()) {
            try {
                \Illuminate\Support\Facades\Artisan::call('weekly-best:generate');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Weekly best generation failed: ' . $e->getMessage());
            }
        }
    }
}