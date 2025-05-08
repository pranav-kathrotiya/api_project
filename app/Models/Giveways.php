<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Giveways extends Model
{
    use HasFactory;
    public function prizes()
    {
        return $this->hasMany(Prize::class, 'giveway_id')->select('prizes.id', 'prizes.giveway_id', 'prizes.name', 'prizes.rank', DB::raw("CONCAT('" . url('uploads/giveaway_prizes') . "/', prizes.image) AS image_url"));
    }
}
