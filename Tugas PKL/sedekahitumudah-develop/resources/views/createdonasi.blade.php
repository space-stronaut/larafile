@extends('layouts.front-layouts')
@section('title')
    {{$program->title}}
@endsection

@section('style')
<style>
body{
    background: #f2f3f4 !important;
}
        
.nav-bar{
    background-color: #fff;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

.logo{
    color: #fff;
    background-color: #0af;
}

.nav-bar li a{
    text-decoration: none;
    color: #333;
}

.info-program{
    background: #fff;
    min-height: 110px;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    margin-top: 20px; 
    padding-right: 100px;  
    font-size: 12px; 
}

.info-program .col-3{
    line-height: 110px; 
}

img{
    width: 90%;
}

.form-donatur{
    border-radius: 8px;
    margin-top: 25px;
    background: #fff;
    border: 1px solid #eaeaea;
    padding-bottom: 20px;
    padding-right: 100px;
    font-size: 12px;
}

.input-group-text{
    padding-left: 20px;
    font-size: 12px;
    font-weight: 700;
    border: .5px solid;
    border-color:  #eaeaea;
    border-radius: 20px;
    background-color: transparent;
}   

.input-donasi{    
    border: .5px solid #eaeaea;
    border-radius: 20px;
    font-size: 12px;
}

.amount{
    border-left: 0px; 
}


.btn-donasi{
    width: 100%;
    height: 42px;
    background: #0af;
    color: #fff;
    font-size: 14px;
    font-weight: 200;
    border-radius: 25px;
}

.grid-button{
    display: grid;
    grid-template-columns: repeat(3,1fr);
    grid-gap: 15px;
    margin-top: 15px;
}

.grid-button .btn-amount{          
    font-size: 12px;
    color: #000000;
    border: 2px solid;
    border-color:#eaeaea;
    border-radius: 20px;
    text-align: center;
    background-color: #fff;
    cursor: pointer;
    transition: .2s;
    width: 100%;
    padding: 4px;
}

.grid-button .btn-amount:hover{
    background: #0af;
    color: #fff;
    font-weight: 600;
}

.active-amount {
    background-color: #0af;
    color: #fff;
    background-size: 100%;
}

.textarea-donasi{
    border-radius: 15px;
    resize: none;
    font-size: 12px;
}

/* .grid-button, ::after, ::before {
    box-sizing: border-box;
} */

input.input-donasi{

}

input.larger-checkbox {
    width: 20px;
    height: 20px;
}

label.label-checkbox {
    margin: 6px 10px;
    font-size: 11px;
}

a.back-button {
    font-size: 11px; 
    text-decoration: none;
    color: #000000;
}
.image-program {
    background-size: contain;
    background-repeat: no-repeat;
    height: 75px;
}
</style>    
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-7 offset-lg-3">
            
            <div class="info-program">
                <div class="row">
                    <div class="col-3 offset-1 mt-3 image-program" style="background-image: url({{$program->getFoto()}});"></div>

                    <div class="col-8 mt-4">
                        <span>Anda akan berdonasi untuk :</span>  
                        <p><strong>{{$program->title}}</strong></p>
                    </div>
                </div>
            </div>

            <form action="/donasi/{{$program->id}}/donasi/store" method="post">
                    {{ csrf_field() }}
                <div class="form-donatur">
                    <div class="container mt-4">
                        <div class="container">
                            @if (!Auth::check())
                                <div class="mb-3"><a href="/login">Login</a> atau Lengkapi data dibawah ini</div>
                            @endif

{{---==================== SectionNominalDonasi =========================--}}

                            <label class="sr-only" for="nominal">Nominal donasi</label>
                            <label class="label font-weight-bold">Masukan Donasi terbaik Anda</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp</div>
                                </div>
                                <input type="number" name="nominal_donasi" min="1000" class="form-control input-donasi amount" id="nominal" placeholder="Berikan nominal sedekah terbaikmu" required>
                            </div>
                            <input type="hidden" name="program_id" value="{{$program->id}}">                            
                            <div class="grid-button">
                                <input type="button" class="btn-amount" onclick="change(25000)" value="Rp 25,000">
                                <input type="button" class="btn-amount" onclick="change(50000)" value="Rp 50,000">                               
                                <input type="button" class="btn-amount" onclick="change(100000)" value="Rp 100,000">                                                                                             
                            </div>
                            <div class="grid-button mt-3 mb-4">
                                <input type="button" class="btn-amount" onclick="change(250000)" value="Rp 250,000">                               
                                <input type="button" class="btn-amount" onclick="change(500000)" value="Rp 500,000">                             
                                <input type="button" class="btn-amount" onclick="change(1000000)" value="Rp 1,000,000">
                            </div>
                            
                            {{-- Script untuk ubah value nominal dengan klik button --}}
                            <script>
                                function change(value){
                                    document.getElementById("nominal").value = value;                                    
                                }                                
                            </script>
                            




{{---==X============X==== EndofSectionNominalDonasi ====X================X===--}}

                            @if (Auth::check())
                                <input type="hidden" name="users_id" value="{{Auth::user()->id}}">
                                <div class="form-group">
                                    <label class="label font-weight-bold">Nama Lengkap</label>
                                    <input class="form-control input-donasi" type="text" readonly name="nama_donatur" value="{{Auth::user()->name}}">                                
                                </div>

                                <div class="form-group">
                                    <label class="label font-weight-bold">Email</label>
                                    <input class="form-control input-donasi" type="text" readonly name="email" value="{{Auth::user()->email}}">
                                </div>

                                <div class="form-group">
                                    <label class="label font-weight-bold">No. Whatsapp</label>
                                    <input class="form-control input-donasi" type="text" name="no_telp" value="{{Auth::user()->no_hp}}">
                                </div>

                            @else    
                            
                                <div class="form-group">
                                    <label class="label font-weight-bold">Masukan Nama Lengkap</label>
                                    <input type="text" name="nama_donatur" class="form-control input-donasi " placeholder="Tulis nama lengkapmu" required>
                                </div>
    
                                <div class="form-group">
                                    <label class="label font-weight-bold">Email</label>
                                    <input type="email" name="email" class="form-control input-donasi" placeholder="Masukkan emailmu (opsional)">
                                </div>

                                <div class="form-group">
                                    <label class="label font-weight-bold">No. Whatsapp</label>
                                    <input class="form-control input-donasi" type="text" name="no_telp" placeholder="Masukkan nomor whatsappmu" required>
                                </div>
                            @endif
    
                                <div class="form-group">
                                    <textarea name="dukungan" rows="4" class="form-control textarea-donasi" placeholder="Tuliskan doa dan dukungan Anda"></textarea>
                                </div>
                                <div class="form-check">
                                    <input id="check" type="checkbox" name="nama_donatur" class="form-check-input input-donasi larger-checkbox" value="Hamba Allah">
                                    <label for="check" class="form-check-label label-checkbox">Jadikan Donasi Anonim</label>
                                </div>

                                <button class="btn btn-donasi mt-4 mb-3" type="submit">Donasi Sekarang</button>  

                                <div class="text-center">                            
                                    <a class="back-button" href="/donasi/{{ $program->id }}"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </div>
                            
                        </div>
                    </div>
                </div>
                
    
            </form>
            


        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(".btn-amount").on('click', function() {    
    $(".btn-amount").removeClass('active-amount');
    $(this).addClass('active-amount');
});
</script>
@endsection