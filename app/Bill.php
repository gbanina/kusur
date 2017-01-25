<?php

namespace App;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

    class Bill extends Eloquent {
         use SoftDeletes;

         protected $dates = ['deleted_at'];
    }
