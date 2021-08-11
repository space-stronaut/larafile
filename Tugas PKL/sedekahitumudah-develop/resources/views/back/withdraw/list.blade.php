@extends('layouts.back-layouts')
@section('title')
    Dashboard
@endsection


@section('content')
    <div class="material-card card">
            <div class="card-body">
                    <h4 class="card-title">Daftar Program</h4>

                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <div class="table-responsive">
                            <table id="zero_config" class="table no-wrap user-table" role="grid" aria-describedby="zero_config_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Tanggal<br> Pengajuan</th>
                                            <th>Judul Program</th>
                                            <th>Bank Tujuan</th>
                                            <th>Nama penerima</th>
                                            <th>Nomor Rekening</th>
                                            <th>Pencairan</th>
                                            <th>Potongan<br> (persentase)</th>
                                            <th>Withdrawal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach ($fundsReq as $funds)
                                    <tr role="row" class="odd">
                                        <td>{{ $funds->created_at->toDateString() }}</td>
                                        <td>
                                            {{ $funds->program->title }}
                                        </td>
                                        
                                        <td>
                                            {{$funds->bank_tujuan}}
                                        </td>
                                        
                                        <td>{{ $funds->pemegang_bank }}</td>
                                        <td>{{ $funds->nomor_rek }}</td>
                                        <td>@rupiah($funds->jumlah_tarik)</td>
                                        <td>@if ($funds->percentage > 0)
                                            @rupiah($funds->potongan) ({{ $funds->percentage }}%)
                                        @endif </td>
                                        <td><b>@rupiah($funds->jumlah_terima)</b></td>
                                        <td>
                                            @if ($funds->status == 'pending')
                                                <p class="badge badge-warning">Pending</p>
                                            @endif

                                            @if ($funds->status == 'pending_payment')
                                                <p class="badge badge-warning">Pending Payment</p>
                                            @endif

                                            @if ($funds->status == 'approved')
                                                <p class="badge badge-success">Approved</p>
                                            @endif

                                            @if ($funds->status == 'rejected')
                                                <p class="badge badge-danger">Rejected</p>
                                            @endif

                                            @if ($funds->status == 'paid')
                                                <p class="badge badge-success">Completed</p>
                                            @endif
                                        </td>   
                                        <td style="color: white">
                                            @if ($funds->status == 'pending')
                                                <a class="btn btn-md btn-success popup-confirm-action" href="/admin/withdraw/{{$funds->id}}/approved">Approve</a>
                                                <a class="btn btn-md btn-danger" data-toggle="modal" data-target="#staticBackdrop">Reject</a>  

                                                {{-- <button type="button" class="btn btn-primary popup-confirm-action" data-toggle="modal" data-target="#staticBackdrop">
                                                    Launch static backdrop modal
                                                </button> --}}
                                                
                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="color: black">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Tolak Pengajuan Pencairan Dana</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <form action="/admin/withdraw/{{$funds->id}}/rejected/alasan" method="POST">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                  <label for="message-text" class="col-form-label">Alasan :</label>
                                                                  <input type="text" class="form-control" name="alasan">
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success">Send</button>     
                                                            </div>
                                                        </form>
                                                    </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($funds->status == 'pending_payment')
                                                <a class="btn btn-md btn-success popup-confirm-action" href="/admin/withdraw/{{$funds->id}}/paid">Paid</a>   
                                            @endif

                                            @if ($funds->status == 'rejected')
                                                <a class="btn btn-md btn-success popup-confirm-action" href="/admin/withdraw/{{$funds->id}}/pending">pending</a>
                                                <a class="btn btn-md btn-danger popup-confirm-action" href="/admin/withdraw/{{$funds->id}}/rejected">delete</a>
                                                
                                                
                                            @endif
                                            
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>
                    </div>
                </div>
    </div>

@endsection
@section('script')
<script src="{{asset('back-assets/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
@endsection