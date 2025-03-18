<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    use HasFactory;

    protected $table = 'tempatibadah';
    protected $guarded = ['id'];

    public function points()
    {
        return $this->select(DB::raw('id, name, description, religion, ST_AsGeoJSON(geom) as geom, image'))->get();
    }

    public function point($id)
    {
        return $this->select(DB::raw('id, name, description, religion, ST_AsGeoJSON(geom) as geom, image'))->where('id', $id)->get();
    }
}
