<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppFeedback extends Model
{
    use HasFactory;

    protected $table = 'app_feedbacks';

    protected $fillable = [
        'user_id',
        'rating',
        'feedback_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
