<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait CreateComId 
{
    protected static function bootCreateComId()
    {
        static::creating(function ($model) {
            $model->com_id = Auth::user()->com_id;
        });
    }
}
