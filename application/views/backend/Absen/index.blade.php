@extends('template.backend.layout')
@section('content')
<h2 class="title">Daftar Absen</h2>
<hr>
<div class="pt-3 pb-3" >
    <a href="{{ site_url('backend/absen/tambah') }}" class="btn btn-outline-primary"><span class="bi-plus-circle"></span> Tambah</a>
</div>
<div class="table-responsive pt-3">
  <table class="table table-border table-sm" id="absen_table">
    <thead>
      <tr>
        <th scope="col">Kode Absen</th>
        <th scope="col">Created At</th>
        <th scope="col">Expired At</th>
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
      $.post('{{ site_url("backend/absen/hapus")  }}', {'id' : id}, function(data){
          if(data){
            $('#absen_table').DataTable().ajax.reload(null, false);
          }
      });
    }
  });

  $(document).ready( function () {
    $('#absen_table').DataTable( {
        serverSide: true,
        ajax: {
          url : '{{ site_url("backend/dt/absen/index") }}',
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
              return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
                        + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
                        + '<a class="btn btn-sm btn-primary" href={{ site_url("backend/absen/lihat/") }}'+ data +' ><span class="bi-eye"></span></a>'
                      '</div>';
            },
          },
          
        ],
    });
  });
</script>
@endsection