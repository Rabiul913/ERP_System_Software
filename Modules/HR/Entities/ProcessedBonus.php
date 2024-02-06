<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class ProcessedBonus extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function processedBonusDetails()
    {
        return $this->hasMany(ProcessedBonusDetail::class);
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }
}
