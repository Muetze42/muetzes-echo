<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Command extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'command',
        'content',
        'rights',
        'channels',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rights'   => 'array',
        'channels' => 'array',
    ];

    /**
     * Get the bot of this command
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
