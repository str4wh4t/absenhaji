@php
use Orm\Absen;
use Carbon\Carbon;
@endphp

@extends('template.backend.layout')
@section('content')
<h2 class="title">Riwayat Absen</h2>
<hr>
@if($session['role']->rolename == \Orm\Role::ROLE_ADMIN)
<div class="pt-3 pb-3" >
    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <span class="bi-file-earmark-text"></span> Laporan
    </button>

    <a class="btn btn-success" href="{{ site_url('backend/absen/cetak/' . $date_start->format('Y-m-d') . '/' . $date_end->format('Y-m-d') . '/0' ) }}">
      <span class="bi-file-earmark-text"></span> Cetak .xls
    </a>
</div>
@endif
<div class="table-responsive pt-3">
<div>
    <p>Ket :</p>
    <p><span style="background-color: #ffcccc ; border: solid #000000 1px;">&nbsp&nbsp&nbsp&nbsp&nbsp</span> Terlambat absen</p>
</div>
<table class="table table-bordered border-secondary table-sm" id="user_absen_table">
    <tr>
        <td rowspan="2" ><b>No</b></td>
        <td rowspan="2" ><b>Nama</b></td>
        <td rowspan="2" ><b>Bidang</b></td>
        <td rowspan="2" ><b>Instansi</b></td>
        <td rowspan="2" ><b>Jabatan</b></td>
        <td rowspan="2" ><b>Struktural</b></td>
        <td colspan="{{ $period->count() }}" ><b>Absensi</b></td>
    </tr>
    <tr>
        @foreach($period AS $date)
            <td >{{ $date->format('Y-m-d') }}</td>
        @endforeach
    </tr>
    @php $no = 1; @endphp
    @foreach($user_list as $user)
    <tr>
        <td >{{ $no }}</td>
        <td >{{ $user->fullname }}</td>
        <td >{{ $user->bidang->bidangname ?? '-' }}</td>
        <td >{{ $user->instansi->instansiname ?? '-' }}</td>
        <td >{{ $user->jabatan->jabatanname ?? '-' }}</td>
        <td >{{ $user->struktural->jabatanname ?? '-' }}</td>
        @php 
        $array_user_absen_date_checking = []; 
        $found = false; 
        $terlambat = true; 
        $absen_at = ''; 
        $user_absen_id = null;
        @endphp
        @foreach($period AS $date)
            @foreach($user->absen AS $absen)
                @php 
                $array_user_absen_date_checking = []; 
                $found = false; 
                $terlambat = true; 
                $absen_at = ''; 
                $expired_at = Carbon::createFromFormat('Y-m-d H:i:s',  $absen->expired_at);
                @endphp
                @if($date->isSameDay($expired_at))
                    @php 
                    if(!isset($array_user_absen_date_checking[$user->id])){
                        $array_user_absen_date_checking[$user->id] = [];
                    } 

                    if(in_array($date->format('Y-m-d'),$array_user_absen_date_checking[$user->id])){
                        continue;
                    }

                    $array_user_absen_date_checking[$user->id][] = $date->format('Y-m-d');
                    $found = true; 
                    $userAbsen = $absen->userAbsen()->where('user_id', $user->id)->first();
                    $terlambat = $userAbsen->stts ? false : true;
                    $absen_at = $userAbsen->created_at->format('H:i:s');
                    $user_absen_id = $userAbsen->id;
                    break;
                    @endphp
                @endif
            @endforeach

            @if($found)
                @if($terlambat)
                    <td style="background-color: #ffcccc ;" >{{ $absen_at }} <a href="#" data-id="{{ $user_absen_id }}" class="aHapus">hapus</a></td>
                @else
                    <td >{{ $absen_at }} <a href="#" data-id="{{ $user_absen_id }}" class="aHapus">hapus</a></td>
                @endif
            @else
                @php
                $selected_absen = Absen::whereDate('expired_at', $date)->first();
                @endphp
                @if(!empty($selected_absen))
                <td ><a href="{{ site_url('backend/absen/manual/' . $user->id . '/' . $selected_absen->id) }}">isi</a></td>
                @else
                <td >-</td>
                @endif
            @endif

        @endforeach
    </tr>
    @php $no++; @endphp
    @endforeach
</table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Periode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="tgl_awal_report" class="form-label">Tgl Awal</label>
            <input type="text" class="form-control datetimepicker" id="tgl_awal_report">
          </div>
          <div class="mb-3">
            <label for="tgl_akhir_report" class="form-label">Tgl Akhir</label>
            <input type="text" class="form-control datetimepicker" id="tgl_akhir_report">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_laporan" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">

   $(document).on('click', '.aHapus', function(){
    if (confirm('yakin akan menghapus')) {
      let id = $(this).data('id');
      $.post('{{ site_url("backend/userabsen/hapus")  }}', {'id' : id}, function(data){
          if(data){
            window.location.reload();
          }
      });
    }
    return false;
  });
  
  $(document).on('click', '#btn_submit_laporan', function(){
      let start_date = $('#tgl_awal_report').val();
      let end_date = $('#tgl_akhir_report').val();
      if(!start_date.length || !end_date.length){
        alert('Tgl tidak boleh kosong');
        return;
      }

      let strWindowFeatures = "location=yes,height=570,width=800,scrollbars=yes,status=yes";
      let url = "{{ site_url('backend/absen/cetak') }}/" + start_date + '/' + end_date;
      let win = window.open(url, "_self", strWindowFeatures);
  });

  $(document).ready( function () {
    $('.datetimepicker').datetimepicker({timepicker:false, format:'Y-m-d'});
  });
</script>
@endsection