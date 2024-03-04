@extends('template.backend.layout')
@section('content')
<h2 class="title">Riwayat Absen Harian</h2>
<hr>
@if($session['role']->rolename == \Orm\Role::ROLE_ADMIN)

@endif

<form id="myForm" method="post">
    <input type="text" id="dateInput" name="date" class="datetimepicker"  placeholder="Pilih Tanggal">
</form>


@if($tgl)
  <div class="table-responsive pt-3">
  <table class="table table-border table-sm">
    <thead>
      <tr>
      @foreach ($field_names as $name)
            <th scope="col">{{ $name }}</th>
            @endforeach
          </tr>
    </thead>
    <tbody>
      
    <?php 
    foreach($data_absen as $row){
      echo '<tr>';
      foreach($field_names as $name){
          echo '<td>' . $row->$name . '</td>';
      }
      echo '</tr>';
  }
  ?>  
    </tbody>
  </table>
  </div>
@endif








    

@endsection

@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#dateInput').on('change', function() {
        var date = $(this).val();
        document.location.replace('<?= site_url('backend/absen/riwayat_harian') ?>?date=' + date)
    });
});

    $(document).ready( function () {
    $('.datetimepicker').datetimepicker({timepicker:false, format:'Y-m-d', placeholder: 'Pilih Tanggal'});
    
    // $('#user_absen_table').DataTable( {
    //     serverSide: true,
    //     ajax: {
    //       url : '{{ site_url("backend/dt/absen/riwayat_harian") }}',
    //       type: 'POST',
    //       'data' : function(d){
    //         d.searchText = 'text';
    //       },
    //     },
    //     columns: [
    //       {
    //         data: 1,
    //       },
    //       {
    //         data: 2,
    //       },
    //       {
    //         data: 3,
    //       },
    //       {
    //         data: 0,
    //         render: function (data, type) {
    //           if(ROLE == 'non_admin'){
    //             return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
    //                       // + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
    //                       + '<a class="btn btn-sm btn-primary" href={{ site_url("backend/userAbsen/lihat/") }}'+ data +' ><span class="bi-eye"></span></a>'
    //                     '</div>';
    //           }else{
    //             return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'
    //                       + '<button type="button" class="btn btn-sm btn-danger btnHapus" data-id="'+ data +'" ><span class="bi-trash3"></span></button>'
    //                       + '<a class="btn btn-sm btn-primary" href={{ site_url("backend/userAbsen/lihat/") }}'+ data +' ><span class="bi-eye"></span></a>'
    //                     '</div>';
    //           }
    //         },
    //       },
          
    //     ],
    //     order: [[2, 'desc']],
    // });

  });
</script>
@endsection