@extends('layouts.middle-layouts')

@section('title')
Form Pencairan Dana
@endsection

@section('style')
<style>
.card p {
    font-weight: normal;
}
.form-group #lainnya > label {
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
    color: rgba(9,38,74,.6);
}
.required {
    color: #ff0000;
}
</style>    
@endsection
@section('content')
<section class="section-1">
    <div class="row">
        
        <div class="col-lg-8">
            <div class="card card-signin">
                <div class="card-body">
                    <div class="card-rinci">
                        <h4 class="card-title">Form Pencairan Dana</h4>
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/withdraw/preview" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                        <label for="jumlah_tarik" class="control-label mt-4">Jumlah dana yang akan ditarik <span class="required">*</span></label>
                        <input type="text" name="jumlah_tarik" class="form-control" id="jumlah_tarik" value="{{ $available_balance }}">

                        <label for="nama" class="control-label mt-4">Pilih Bank Tujuan <span class="required">*</span></label>
                        <select onchange="onChangeHandler()" name="bank_tujuan" class="form-control" id="bank" required>
                            <option value="">- Pilih -</option>
                            @foreach ($bank_tujuan as $key => $label)
                            <option value="{{ $key }}" @if (old('bank_tujuan')==$key)
                                selected="selected"
                            @endif>{{ $label }}</option>
                            @endforeach
                        </select>
                        
                        <div id="lainnya" style="display: none;">    
                            <label for="recipient-name" class="control-label mt-4">Nama Bank</label>
                            <input type="text" name="bank_name" class="form-control" id="recipient-name1" value="">
                            <label for="recipient-name" class="control-label mt-4">Cabang</label>
                            <input type="text" name="cabang" class="form-control" >
                        </div>

                        <label for="recipient-name" class="control-label mt-4">Nomor Rekening Tujuan <span class="required">*</span></label>
                        <input type="text" name="nomor_rek" class="form-control" id="recipient-name1" value="{{ old('nomor_rek') }}" required>
                        <label for="recipient-name" class="control-label mt-4">Nama Pemegang Rekening <span class="required">*</span></label>
                        <input type="text" name="pemegang_bank" class="form-control" value="{{ old('pemegang_bank') }}" required>
                    
                        <input type="text" name="program_id" value="{{ $program->id }}" style="display: none">
                
                        <button type="submit" class="btn btn-primary mt-3">Ajukan Pencairan</button>
                    </div>
                    </form>
                
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
                    </span></p><hr>
                    
                    <p>Jumlah dana yang bisa dicairkan
                    <h5 class="mt-2">@rupiah($available_balance)</h5></p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script>
const dropdown = document.getElementById("bank")
const textInput = document.getElementById("lainnya")
const onChangeHandler = () => {
    dropdown.value === "misc" ? textInput.style.display = "block" : textInput.style.display = "none";
}
</script>
@endsection

