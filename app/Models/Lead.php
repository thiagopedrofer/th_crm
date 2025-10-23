<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'state',
        'lead_type_id',
        'user_id',
        'status',
        'desired_credit_amount',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function leadType(): BelongsTo
    {
        return $this->belongsTo(LeadType::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }
}
