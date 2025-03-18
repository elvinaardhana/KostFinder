<?php

namespace App\Http\Controllers;

use App\Models\Points;
use App\Models\Polylines;
use App\Models\Polygon;
use App\Models\AdminPoligon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //memanggil model yang sudah ada pada project Laravel
    public function __construct() {
        $this->points = new Points();
        $this->polylines = new Polylines();
        $this->polygons = new Polygon();
    }

    public function index()
    {
    $totalPoints = $this->points->count();
    $totalPolylines = $this->polylines->count();
    $totalPolygons = $this->polygons->count();

    $religionCounts = [
        'islam' => $this->points->where('religion', 'Islam')->count(),
        'kristen' => $this->points->where('religion', 'Kristen')->count(),
        'katolik' => $this->points->where('religion', 'Khatolik')->count(),
        'konghucu' => $this->points->where('religion', 'Konghucu')->count(),
        'hindu' => $this->points->where('religion', 'Hindu')->count(),
        'buddha' => $this->points->where('religion', 'Buddha')->count(),
    ];

    $data = [
        "title" => "Dashboard",
        "total_points" => $totalPoints,
        "total_polylines" => $totalPolylines,
        "total_polygons" => $totalPolygons,
        "religion_counts" => $religionCounts,
    ];
    return view('dashboard', $data);
    }
}
