<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Command extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_protected' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_protected',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'channels' => 'array',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($command) {
            if ($command->channels) {
                $command->channels = array_map('replaceWorking', $command->channels);
            }
            $command->command = replaceWorking($command->command);
        });
    }

    /**
     * Get the bot of this command
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
