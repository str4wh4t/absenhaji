@extends('template.backend.layout')
@section('content')
<h2 class="title">Daftar User</h2>
<hr>
<div class="pt-3 pb-3" >
    <a href="{{ site_url('backend/user/tambah') }}" class="btn btn-outline-primary"><span class="bi-plus-circle"></span> Tambah</a>
</div>
<div class="table-responsive pt-3">
  <table class="table table-border table-sm" id="user_table">
    <thead>
      <tr>
        <th scope="col">Fullname</th>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Created At</th>
        <th scope="col">Aktif</th>
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
      $.post('{{ site_url("backend/user/hapus")  }}', {'id' : id}, function(data){
          if(data){
            $('#user_table').DataTable().ajax.reload(null, false);
          }
      });
    }
  });

  $(document).ready( function () {
    $('#user_table').DataTable( {
        serverSide: true,
        ajax: {
          url : '{{ site_url("backend/dt/user/index") }}',
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
            data: 4,
          },
          {
            data: 5,
          },
          {
            data: 6,
            render: function (data, type) {
              let aktif = 'TIDAK';
              if(data == '1'){
                aktif = 'YA';
              }
              return aktif;
            }
          },
          {
            data: 0,
            render: function (data, type) {
              return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
                        + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
                        + '<a class="btn btn-sm btn-info" href={{ site_url("backend/user/edit/") }}'+ data +' ><span class="bi-pencil"></span></a>'
                      '</div>';
            },
          },
          
        ],
    });
  });
</script>
@endsection