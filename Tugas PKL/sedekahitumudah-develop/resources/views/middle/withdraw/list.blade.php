@extends('layouts.middle-layouts')
@section('title')
    Status Pencairan Dana
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('back-assets/dist/css/style.min.css')}}">
    <style>
        td{
            width: 390px !important;
        }
    </style>
@endsection

@section('content')

    <div class="material-card card">
        <div class="row">
            <div class="col-6">
                {{-- Search Box --}}
                <form action="/withdraw/list" method="GET">
                    <div class="float-left mt-4 ml-4 input-group col-6">
                        <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ old('q') }}" aria-describedby="button-addon2">
                        <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
                </form>
                {{-- End of search box --}}
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Program</th>
                        <th>Jumlah Tarik</th>
                        <th>Bank Tujuan</th>
                        <th>No. Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($withdraws as $withdraw)
                    <tr>
                        <td>{{ $withdraw->nomor_transaksi }}</td>
                        <td>{{ $withdraw->created_at->toDateString() }}</td>
                        <td>{{ $withdraw->program->title }}</td>
                        <td>Rp. {{ number_format($withdraw->jumlah_tarik,0,"",".") }},-</td>
                        <td>{{ $withdraw->bank_name }}</td>
                        <td>{{ $withdraw->nomor_rek }}</td>
                        <td>{{ $withdraw->pemegang_bank }}</td>
                        <td>{{ $withdraw_status[$withdraw->status] }}</td>
                        <td><a class="btn btn-light" href="/withdraw/{{ $withdraw->id }}"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          {{ $withdraws->links() }}          
        </ul>
      </nav>
    
      <p class="text-center">Total Data: <b>{{ $total_data }}</b></p>

@endsection
@section('script')
<script src="{{asset('back-assets/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
@endsection
