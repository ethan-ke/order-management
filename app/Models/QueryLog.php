<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueryLog extends BaseModel
{
    /**
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'phone', 'phone');
    }
}
