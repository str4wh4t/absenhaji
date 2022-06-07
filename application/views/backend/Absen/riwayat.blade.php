@extends('template.backend.layout')
@section('content')
<h2 class="title">Riwayat Absen</h2>
<hr>
<!-- <div class="pt-3 pb-3" >
    <a href="{{ site_url('backend/absen/tambah') }}" class="btn btn-outline-primary"><span class="bi-plus-circle"></span> Tambah</a>
</div> -->
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

  $(document).ready( function () {
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
    });
  });
</script>
@endsection