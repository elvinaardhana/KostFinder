@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3>Data Point</h3>
            </div>
            <div class="card-body">
                {{-- menambahkan html id example dari bootstrap 5--}}
                <table class="table table-boardered table-striped" id="example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Religion</th>
                            <th>Coordinate</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1 @endphp
                        @foreach ($points as $p)
                            @php
                                $geometry = json_decode($p->geom);
                            @endphp
                            <tr>
                                <td> {{ $no++ }}</td>
                                <td> {{ $p->name }}</td>
                                <td> {{ $p->description }}</td>
                                <td> {{ $p->religion }}</td>
                                <td> {{ $geometry->coordinates[1] . ', ' . $geometry->coordinates[0]}}</td>
                                <td> <img src ="{{ asset('storage/images/' . $p->image) }}" alt="" width="200"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    {{-- penambahan library CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
@endsection

@section('script')
    {{-- penambahan library javascript --}}
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
@endsection
