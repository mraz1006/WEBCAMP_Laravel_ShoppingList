<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completed_Shopping_Lists extends Model
{
    use HasFactory;
        /**
     * 複数代入不可能な属性
     */
    protected $guarded = [];
    protected $table = 'completed_shoppinglists';
}
