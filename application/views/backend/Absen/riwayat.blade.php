@extends('template.backend.layout')
@section('content')
<h2 class="title">Riwayat Absen</h2>
<hr>
@if($session['role']->rolename == \Orm\Role::ROLE_ADMIN)
<div class="pt-3 pb-3" >
    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <span class="bi-file-earmark-text"></span> Laporan
    </button>
</div>
@endif
<div class="table-responsive pt-3">
  <table class="table table-border table-sm" id="user_absen_table">
    <thead>
      <tr>
        <th scope="col">Nama</th>
        <th scope="col">Email</th>
        <th scope="col">Waktu</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
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
  $(document).on('click', '.btnHapus', function(){
    if (confirm('yakin akan menghapus')) {
      let id = $(this).data('id');
      $.post('{{ site_url("backend/userabsen/hapus")  }}', {'id' : id}, function(data){
          if(data){
            $('#user_absen_table').DataTable().ajax.reload(null, false);
          }
      });
    }
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

    $('#user_absen_table').DataTable( {
        serverSide: true,
        ajax: {
          url : '{{ site_url("backend/dt/absen/riwayat") }}',
          type: 'POST',
          'data' : function(d){
            d.searchText = 'text';
          },
        },
        columns: [
          {
            data: 1,
          },
          {
            data: 2,
          },
          {
            data: 3,
          },
          {
            data: 0,
            render: function (data, type) {
              if(ROLE == 'non_admin'){
                return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
                          // + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
                          + '<a class="btn btn-sm btn-primary" href={{ site_url("backend/userabsen/lihat/") }}'+ data +' ><span class="bi-eye"></span></a>'
                        '</div>';
              }else{
                return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
                          + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
                          + '<a class="btn btn-sm btn-primary" href={{ site_url("backend/userabsen/lihat/") }}'+ data +' ><span class="bi-eye"></span></a>'
                        '</div>';
              }
            },
          },
          
        ],
        order: [[2, 'desc']],
    });
  });
</script>
@endsection