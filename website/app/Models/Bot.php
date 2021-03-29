<?php

namespace App\Models;

//@Todo: No encryption in this example
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'token',
        'refresh_token',
        'refreshed_at',
        'channels',
        'user_id',
        'prefix',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'channels'     => 'array',
        'refreshed_at' => 'datetime',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($bot) {
            if ($bot->channels) {
                $bot->channels = array_map('replaceWorking', $bot->channels);
            }
            $bot->prefix = replaceWorking($bot->prefix);
        });
        static::updated(function ($bot) {
            Artisan::call('bot:restart ' . $bot->id);
        });
    }

    /**
     * Get the user of this bot
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }

    /**
     * Get the commands for this bot
     */
    public function commands()
    {
        $this->hasMany(Command::class);
    }
}
