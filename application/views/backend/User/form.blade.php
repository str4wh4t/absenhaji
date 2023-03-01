@php
use Orm\User;
@endphp
@extends('template.backend.layout')
@section('css')
<style type="text/css">

</style>
<link href="{{ base_url('node_modules') }}/select2/dist/css/select2.min.css"" rel="stylesheet">
@endsection
@section('content')
<h2 class="title">{{ $title }}</h2>
<hr>
<form class="col-lg-12" method="post" action="{{ site_url($action) }}">
  @if(!empty($list_errors))
    <div class="alert alert-danger fs-6" role="alert">
        <ul class="notif_error">
        @foreach($list_errors as $err)
            {!! $err !!}
        @endforeach
        </ul>
    </div>
    @endif

    @if($notif_sukses != '')
    <div class="alert alert-success" role="alert">
        {{ $notif_sukses }}
    </div>
    @endif

    @if(isset($flash_session['activation_success']))
    <div class="alert alert-success" role="alert">
        {{ $flash_session['activation_success'] }}
    </div>
    @endif
  <div class="mb-3">
    <label for="fullname" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $input['fullname'] }}">
  </div>
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" value="{{ $input['username'] }}">
  </div>
  <div class="mb-3">
    <label for="bidang" class="form-label">Bidang</label>
    <?= form_dropdown('bidang_id', $bidang, $input['bidang_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  <div class="mb-3">
    <label for="instansi" class="form-label">Instansi</label>
    <?= form_dropdown('instansi_id', $instansi, $input['instansi_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  
  <div class="row mt-4 mb-0">
    <div class="col-lg-3 mb-3 pt-2">
        <div class="radio-group">
            <div class="radio radio-info radio-inline">
                <input type="radio" id="radioc" value="2" name="stts_jabatan" {{ $input['stts_jabatan'] == "2" ? "checked" : null }}>
                <label for="radioc" class="tooltips" 
                    data-toggle="tooltip" data-placement="top" 
                    data-original-title="Data jabatan">Jabatan Struktural</label>
            </div>
            <div class="radio radio-success radio-inline">
                <input type="radio" id="radiod" value="1" name="stts_jabatan" {{ $input['stts_jabatan'] == "1" ? "checked" : null }}>
                <label for="radiod" class="tooltips" 
                    data-toggle="tooltip" data-placement="top" 
                    data-original-title="Data jabatan">Jabatan Non Struktural</label>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="panel_select_jabatan" style="display: none">
        <div class="form-group">
            <label for="jabatan" class="form-label text-danger" style="margin-bottom: 0;">* silahkan pilih jabatan disini</label>
            <?php echo form_dropdown('jabatan', null, null , 'class="form-control" id="jabatan" data-placeholder="- PILIH -"') ?>
        </div>
    </div>
</div>

 
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="text" class="form-control" id="email" name="email" value="{{ $input['email'] }}">
  </div>
  
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" value="{{ $input['password'] }}">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <a class="btn btn-outline-danger" href="{{ site_url('backend/user') }}" >Kembali</a>
  @if($input['stts'] == '0' && !User::NO_ACTIVATION)
  <a class="btn btn-success" href="{{ site_url('backend/user/activation/'. $input['id'] ) }}" >Aktivasi</a>
  @endif
</form>
@endsection

@section('js')
<script type="text/javascript" src="{{ base_url('node_modules') }}/select2/dist/js/select2.min.js"></script>
<script type="text/javascript">

    // function select_jabatan(param){
    //     var act;
        
    //     if(param == "non_struktural"){
    //         act = 'backend/user/get_jabatan/non_struktural';
    //     }else if(param == "struktural"){
    //         act = 'backend/user/get_jabatan/struktural';
    //     }

    //     $('#jabatan').on(
    //       'select2:open', function() {
    //         document.querySelector('.select2-search__field').focus();
    //       }   
    //     ).select2({
    //     // $('#jabatan').select2({
    //         allowClear:true,
    //         placeholder: 'Ketik nama jabatan',
    //         minimumInputLength: 2,
    //         multiple: false,
    //         ajax: {
    //             url: '<?= base_url() ?>' + act,
    //             dataType: 'json',
    //             quietMillis: 100,
    //                 processResults: function (data) {
    //                     return {
    //                         results: data
    //                     };
    //                 },
    //             cache: true
    //         },
    //     });
    //     $('#jabatan').select2('open');

    //     $('#jabatan').select2({
    //       data: data
    //     });
        
    // }



    function init_select_jabatan(data){
      $('#jabatan').empty();
      $('#jabatan').select2({
        data: data
      });
    }

    function radio_jabatan(){
      var struktural;
      var non_struktural;
      var jabatan_id; 
      var jabatan_name;

      struktural  = "<?= $input['struktural_id'] ?>";
      non_struktural  = "<?= $input['jabatan_id'] ?>";

      // jabatan_id  = "<?= $input['struktural_id'] ?>";
      // jabatan_name = "<?= $input['struktural_id'] ?>";

      if(non_struktural == "0" || non_struktural == "" || non_struktural == null){
          // var $option = $("<option selected></option>").val("1").text("");
          // $('#jabatan').append($option).trigger('change');
      }

      // if(jabatan_id == "0" || jabatan_id == null){
      //     $("#jabatan").val('').trigger('change')
      // }


        // if($('#radioc').is(':checked')) { 
        //     select_jabatan("struktural");
        // }

        // if($('#radiod').is(':checked')) { 
        //     select_jabatan("non_struktural");
        // }
    }

  $(document).on('click', '.btnHapus', function(){

  });

  $(document).ready( function () {

    radio_jabatan();

    $('.chosen').chosen({
        width: "100%"
    });

    $('#jabatan').select2();

    @if($input['stts_jabatan'] != 0)
      $('#panel_select_jabatan').show();
      @if($input['stts_jabatan'] == 1)
      let data =JSON.parse('{!! json_encode($select2_non_struktural_opt) !!}');
      let id = {{ $input['jabatan_id'] }};
      @elseif($input['stts_jabatan'] == 2)
      let data =JSON.parse('{!! json_encode($select2_struktural_opt) !!}');
      let id = {{ $input['struktural_id'] }};
      @endif
      init_select_jabatan(data);
      $("#jabatan").val(id).trigger('change');
    @endif
  });

  $(document).on('click', '[name="stts_jabatan"]', function () {
        $('#panel_select_jabatan').show();
        $('#jabatan').select2('destroy');
        let data
        if($(this).val() == "2"){
          // select_jabatan("struktural");
          // alert('INT')
          data =JSON.parse('{!! json_encode($select2_struktural_opt) !!}');
        }

        if($(this).val() == "1"){
          // select_jabatan("non_struktural");
          // alert('EXT')
          data =JSON.parse('{!! json_encode($select2_non_struktural_opt) !!}');
        }
        init_select_jabatan(data);
    });
</script>
@endsection