<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polylines;

class PolylineController extends Controller
{
    public function __construct()
    {
        $this->polyline = new Polylines();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polylines = $this->polyline->polylines();

        foreach ($polylines as $pl) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($pl->geom),
                'properties' => [
                    'id' => $pl->id,
                    'name' => $pl->name,
                    'description' => $pl->description,
                    'created_at' => $pl->created_at,
                    'updated_at' => $pl->updated_at,
                    'image' => $pl->image
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature,
        ]);

        //dd($polylines);
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
            $filename = time() . '_polyline.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        } else {
            $filename = null;
        }
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // create polyline
        if(!$this->polyline->create($data)) {
            return redirect()->back()->with('error', 'Failed to create polyline');
        }

        // redirect to map
        return redirect()->back()->with('success', 'Polyline created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $polylines = $this->polyline->polyline($id); //supaya yang tertampil hanya 1 polyline sesuai idnya saja

        foreach ($polylines as $pl){
            $feature[] = [
                'type' =>'Feature',
                'geometry'=> json_decode($pl->geom),
                'properties'=>[
                    'id'=> $pl->id, // unique value untuk penghapusan data
                    'name'=>$pl->name,
                    'description'=>$pl->description,
                    'image'=>$pl ->image,
                    'created_at'=>$pl->created_at,
                    'updated_at'=>$pl->updated_at
                ]
            ];
        }

        return response()->json([
            'type'=>'FeatureCollection',
            'features'=>$feature
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $polyline = $this->polyline->find($id);

        $data = [
            'title' => 'Edit Polyline',
            'polyline' => $polyline,
            'id' => $id
        ];

        return view('edit-polyline', $data);
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
            $filename = time() . '_polyline.' . $image->getClientOriginalExtension();
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
            'geom' => $request->geom,
            'image' => $filename
        ];

        // berfungsi untuk mendebug inputan name, description
        // dd($data);

        // update polyline
        if(!$this->polyline->find($id)->update($data)) {
            return redirect()->back()->with('error', 'Failed to update polyline');
        }

        // redirect to map
        return redirect()->back()->with('success', 'Polyline updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // get image (mencari nama gambar dengan id tertentu)
        $image = $this->polyline->find($id)->image;

        // dd($image);

        // delete polyline
        if (!$this->polyline->destroy($id)) {
            return redirect()->back()->with('error', 'Failed to delete polyline');
        }

        // delete image
        if ($image != null) {
            unlink('storage/images/' . $image);
        }

        // redirect to map
        return redirect()->back()->with('success', 'Polyline deleted succesfully');
    }

    public function table() {
        $polylines = $this->polyline->polylines();

        // dd($points);

        $data = [
            'title' => 'Table Polyline',
            'polylines' => $polylines
        ];

        return view('table-polyline', $data);
    }
}
