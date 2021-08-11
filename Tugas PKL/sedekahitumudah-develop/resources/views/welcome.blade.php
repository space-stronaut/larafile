@extends('layouts.front-layouts')
@section('title')
    Sedekah itu Mudah
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('front-assets/css/index.css')}}">
@endsection

@section('content')

        <div class="jumbotron-fluid"></div>

        <div class="aksi">
            <p>Galang Dana untuk Hal yang Anda Perjuangkan</p>
            <a href="/program" class="btn btn-galang">Galang Dana Sekarang</a>
        </div>

        <div class="info">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <span>{{$programYangAda}}</span>
                        <p>Total Campaign</p>
                    </div>

                    <div class="col-4">
                        <span>@rupiah($totalDonasi)</span>
                        <p>Total Donasi</p>
                    </div>

                    <div class="col-4">
                        <span>{{$orangBerdonasi}}</span>
                        <p>Transaksi</p>
                    </div>
                </div>
            </div>
        </div>

    <section class="section-2">
        <div class="header mt-4">
            <span>
                <a href="/category">Categories</a>
            </span>
            <hr>
        </div>

        <div class="content">
            <div class="row">
                @foreach ($categories as $category)
                <div class="col-lg-3 col-md-6 pl-4">
                    <a href="/program/category/{{$category->id}}">
                        <div class="card">
                          <div class="card-header">
                            <img src="{{asset('img/'.$category->photo)}}" alt="">
                          </div>
                            <div class="card-body text-center">
                                {{ $category->category_name}}
                            </div>

                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-2">
        <div class="header mt-4">
            <span>
                Program Pilihan
            </span>
            <hr>
        </div>

        <div class="content">
                <div class="row">
                    @foreach ($programs as $program)
                    <div class="col-lg-3 col-md-6 pl-4">
                        <div class="card">
                            <a href="/donasi/{{$program->id}}">
                            <img src="{{$program->getFoto()}}" alt="Program Image">

                            <div class="container mt-3">
                                    @if ($program->donatur->sum('nominal_donasi') >= $program->donation_target)
                                        <div class="badge badge-success">Terdanai <i class="fa fa-check"></i></div>
                                    @endif
                                    <p class="title">{{$program->title}}</p>
                                    <div class="brief">
                                        <p>{{$program->brief_explanation}}</p>
                                    </div>
                            </div>
                                    <div class="programs-info">
                                    <div class="waktu">
                                        <div class="container">
                                        <span>Kategori</span><p>{{$program->category->category_name}}</p>
                                        <span>Berakhir Pada</span><p>{{$program->time_is_up}}</p>
                                        </div>
                                    </div>

                                    <div class="dana">
                                        <div class="container">
                                        <span>Terkumpul</span><p class="collected">@rupiah($program->donatur->sum('nominal_donasi'))</p>
                                        <span>Target</span><p>@rupiah($program->donation_target)</p>
                                        </div>
                                    </div>
                                    </div>

                                </a>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>

    </section>

    <section class="section-2">
            <div class="header mt-4">
                <span>
                    Program Terbaru
                </span>
                <hr>
            </div>

            <div class="content">
                    <div class="row">
                        @foreach ($programsNew as $newProgram)
                        <div class="col-lg-3 pl-4">
                            <a href="/donasi/{{$newProgram->id}}">
                            <div class="card">
                                <img src="{{$newProgram->getFoto()}}" alt="Program Image">

                                <div class="container mt-3">
                                    @if ($newProgram->donatur->sum('nominal_donasi') >= $newProgram->donation_target)
                                        <div class="badge badge-success">Terdanai <i class="fa fa-check"></i></div>
                                    @endif
                                        <p class="title">{{$newProgram->title}}</p><span>
                                        <div class="brief">
                                            <p>{{$newProgram->brief_explanation}}</p>
                                        </div>
                                </div>
                                <div class="programs-info">
                                        <div class="waktu">
                                            <div class="container">
                                            <span>Kategori</span><p>Kemanusiaan</p>
                                            <span>Berakhir Pada</span><p>{{$newProgram->time_is_up}}</p>
                                            </div>
                                        </div>

                                        <div class="dana">
                                            <div class="container">
                                            <span>Terkumpul</span><p>@rupiah($newProgram->donatur->sum('nominal_donasi'))</p>
                                            <span>Target</span><p>@rupiah($newProgram->donation_target)</p>
                                            </div>
                                        </div>
                                        </div>

                                </div></a>
                            </div>
                        @endforeach
                    </div>
                </div>


        </section>

        <div class="foot text-center">
            <a href="/daftarprogram" class="btn btn-more">Lihat Program Lainnya</a>
        </div>
@endsection
