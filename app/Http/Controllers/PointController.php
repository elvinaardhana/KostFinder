<?php

namespace App\Http\Controllers;

use App\Models\Points;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PointController extends Controller
{
    public function __construct()
    {
        // this point merupakan variabel yang menampung point
        $this->point = new Points();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = $this->point->points();

        foreach ($points as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'religion' => $p->religion,
                    'image' => $p->image
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature,
        ]);

        //dd($points);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // function store memasukkan data ke dalam database
    public function store(Request $request)
    {

        // Validate request
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000' //10 MB
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required',
            'image.mimes' => 'Image must be a file of type: jpeg, jpg, png, giff',
            'image.max' => 'Image must not exceed 10 MB'
        ]);


        // membuat folder image
        if(!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        } else {
            $filename = null;
        }
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'religion' => $request->religion,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // berfungsi untuk mendebug inputan name, description
        // dd($data);

        // create point
        if(!$this->point->create($data)) {
            return redirect()->back()->with('error', 'Failed to create point');
        }

        // redirect to map
        return redirect()->back()->with('success', 'Point created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $points = $this->point->point($id);

        foreach ($points as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'religion' => $p->religion,
                    'image' => $p->image
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $point = $this->point->find($id);

        $data = [
            'title' => 'Edit Point',
            'point' => $point,
            'id' => $id
        ];

        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000' //10 MB
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required',
            'image.mimes' => 'Image must be a file of type: jpeg, jpg, png, giff',
            'image.max' => 'Image must not exceed 10 MB'
        ]);


        // membuat folder image
        if(!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);

            // delete image
            $image_old = $request->image_old;
            if ($image_old != null) {
                unlink('storage/images/' . $image_old);
            }

        } else {
            $filename = $request->image_old;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'religion' => $request->religion,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // berfungsi untuk mendebug inputan name, description
        // dd($data);

        // update point
        if(!$this->point->find($id)->update($data)) {
            return redirect()->back()->with('error', 'Failed to update point');
        }

        // redirect to map
        return redirect()->back()->with('success', 'Point updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // get image (mencari name gambar dengan id tertentu)
        $image = $this->point->find($id)->image;

        // dd($image);

        // delete point
        if (!$this->point->destroy($id)) {
            return redirect()->back()->with('error', 'Failed to delete point');
        }

        // delete image
        if ($image != null) {
            unlink('storage/images/' . $image);
        }

        // redirect to map
        return redirect()->back()->with('success', 'Point deleted succesfully');
    }

    public function table() {
        $points = $this->point->points();

        // dd($points);

        $data = [
            'title' => 'Table Point',
            'points' => $points
        ];

        return view('table-point', $data);
    }

    public function unduhTempatIbadah()
    {
    // Ambil data Points (ganti nama model dan kolom sesuai kebutuhan)
    $tempatIbadah = Points::select('id', 'name', 'description', 'religion', DB::raw('ST_X(geom) as latitude'), DB::raw('ST_Y(geom) as longitude'))->get();

    // Buat header untuk file CSV yang akan diunduh
    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=tempat_ibadah.csv",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

    // Fungsi untuk menulis data CSV
    $callback = function() use ($tempatIbadah) {
        $file = fopen('php://output', 'w');

        // Header kolom
        fputcsv($file, ['ID', 'Name', 'Description', 'Religion', 'Latitude', 'Longitude']);

        // Isi data
        foreach ($tempatIbadah as $tempat) {
            fputcsv($file, [
                $tempat->id,
                $tempat->name,
                $tempat->description,
                $tempat->religion,
                $tempat->latitude,
                $tempat->longitude
            ]);
        }

        fclose($file);
        };
        // Mengembalikan response berupa file CSV
        return response()->stream($callback, 200, $headers);
    }
}
