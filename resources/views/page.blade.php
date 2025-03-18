@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            background-color: rgb(96, 49, 10);
        }

        .text-body-secondary {
            color: white !important;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            margin: 0;
        }

        .card-img-top-container {
            height: 350px;
            /* Atur tinggi gambar yang diinginkan */
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <main>

        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-bold" style="color: white;">Dekat Kampus, Dekat Impian – Temukan Kos Idealmu!</h1>
                    <p class="lead text-body-secondary"> KostFinder adalah solusi pintar untuk mencari kos impian Anda. Dengan pemetaan interaktif, filter pencarian, dan informasi lengkap, menemukan tempat tinggal yang nyaman kini lebih cepat dan praktis!
                    </p>
                    <p>
                        <a class="btn btn-warning my-2" href="{{ route('map') }}">Peta Interaktif</a>
                    </p>
                </div>
            </div>
        </section>

    </main>
@endsection
