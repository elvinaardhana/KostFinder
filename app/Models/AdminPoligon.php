<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPoligon extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $guarded = ['id'];

    public function polygonadmins()
    {
        return $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geom, kab_kota'))->get();
    }
}
