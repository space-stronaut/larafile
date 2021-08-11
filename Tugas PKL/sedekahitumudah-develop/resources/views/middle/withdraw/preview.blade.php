@extends('layouts.middle-layouts')

@section('title')
Form Penarikan Dana
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
                                <td>Jumlah yang akan dicairkan</td>
                                <td>@rupiah($jumlah_tarik)</td>
                            </tr>
                            <tr>
                                <td>Potongan 
                                <span 
                                class="fa fa-question-circle tooltips-pad" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="Sedekah itu Mudah mengambil {{$percentage}}% untuk biaya operasional dari program ini"></span></td>
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
                                <td>{{ $bank_tujuan[$input['bank_tujuan']] }}</td>
                            </tr>
                            <tr>
                                <td>Nomor Rekening Tujuan</td>
                                <td>{{ $input['nomor_rek'] }}</td>
                            </tr>
                            <tr>
                                <td>Nama Pemegang Rekening</td>
                                <td>{{ $input['pemegang_bank'] }}</td>
                            </tr>
                        </table>

                        <form action="/withdraw/create" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="program_id" value="{{ $program->id }}">
                            <input type="hidden" name="jumlah_tarik" value="{{ $jumlah_tarik }}">
                            <input type="hidden" name="percentage" value="{{ $percentage }}">
                            <input type="hidden" name="bank_tujuan" value="{{ $input['bank_tujuan'] }}">
                            <input type="hidden" name="nomor_rek" value="{{ $input['nomor_rek'] }}">
                            <input type="hidden" name="pemegang_bank" value="{{ $input['pemegang_bank'] }}">
                            <input type="hidden" name="bank_name" value="{{ $input['bank_name'] }}">
                            <input type="hidden" name="cabang" value="{{ $input['cabang'] }}">

                            <button type="submit" class="btn btn-primary mt-3">Ajukan Pencairan</button>
                            <a href="/withdraw/form/{{ $program->id }}" class="btn btn-danger text-white mt-3">Batal</a>
                        </form>
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

