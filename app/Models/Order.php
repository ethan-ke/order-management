<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
{

    /**
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
