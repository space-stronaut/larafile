@extends('layouts.middle-layouts')

@section('title')
Rincian Pencairan Dana
@endsection

@section('content')
<section class="section-1">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-signin">
                <div class="card-body">
                    <div class="card-rinci">
                        <h4 class="card-title mb-3">Rincian Pencairan Dana</h4>

                        <table class="table table-bordered">
                            <tr>
                                <td>Nomor Transaksi</td>
                                <td>{{$withdraw->nomor_transaksi}}</td>
                            </tr>
                            <tr>
                                <td>Status Pencairan</td>
                                <td>{{ $withdraw_status[$withdraw->status] }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td>{{ $withdraw->created_at->toDateString() }}</td>
                            </tr>
                            <tr>
                                <td>Terakhir diperbaharui</td>
                                <td>{{ $withdraw->updated_at }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Jumlah yang akan dicairkan</td>
                                <td>@rupiah($withdraw->jumlah_tarik)</td>
                            </tr>
                            <tr>
                                <td>Potongan 
                                <span 
                                class="fa fa-question-circle tooltips-pad" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="Sedekah itu Mudah mengambil {{$withdraw->percentage}}% untuk biaya operasional dari program ini"></span></td>
                                <td>@rupiah($potongan_operasional)</td>
                            </tr>
                            <tr>
                                <td>Dana yang akan diterima</td>
                                <td><b>@rupiah($withdrawal_final)</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Bank Tujuan</td>
                                <td>{{ $bank_tujuan[$withdraw->bank_tujuan] }}</td>
                            </tr>
                            <tr>
                                <td>Nomor Rekening Tujuan</td>
                                <td>{{ $withdraw->nomor_rek }}</td>
                            </tr>
                            <tr>
                                <td>Nama Pemegang Rekening</td>
                                <td>{{ $withdraw->pemegang_bank }}</td>
                            </tr>
                        </table>

                        <a href="/withdraw/list" class="btn btn-light mt-3">Kembali</a>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <img src="{{$program->getFoto()}}">      
                <div class="container mt-3 mb-3">
                    @if ($program->donatur->sum('nominal_donasi') >= $program->donation_target)
                        <span class="badge badge-green">Terdanai <i class="la la-check"></i></span>
                    @endif
                    <h4>{{$program->title}}</h4><br>
                    <p>{{$program->brief_explanation}}</p> <hr>

                    <p>Kategori : <span class="badge badge-warning">
                    {{$program->category->category_name}}
                    </span></p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection
