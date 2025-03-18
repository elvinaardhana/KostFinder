<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "GIWIS"
        ];

        return view('page', $data);
    }

    public function map()
    {
        $data = [
            "title" => "GIWIS",
        ];

        //cek user login atau tidak
        if (auth()->check()){
            return view('index', $data);
        } else {
            return view('index-public', $data);
        }
    }

    public function table()
    {
        $data = [
            "title" => "Table"
        ];

        return view('table', $data);
    }
}
