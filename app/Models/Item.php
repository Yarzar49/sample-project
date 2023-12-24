<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /**
     * Item Model
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-21
     *
     */
    use SoftDeletes;
}
