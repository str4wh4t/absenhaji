@extends('template.backend.layout')
@section('css')
<style type="text/css">

</style>
<link href="{{ base_url('node_modules') }}/select2/dist/css/select2.min.css"" rel="stylesheet">
@endsection
@section('content')
<h2 class="title">{{ $title }}</h2>
<hr>
<form class="col-lg-6" method="post" action="{{ site_url($action) }}">
  <hr>
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
    <?= form_dropdown('bidang', $bidang, $input['bidang_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  <div class="mb-3">
    <label for="instansi" class="form-label">Instansi</label>
    <?= form_dropdown('instansi', $instansi, $input['instansi_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  
  <div class="row mt-4 mb-3">
    <div class="col-lg-3 mb-3">
        <div class="radio-group">
            <div class="radio radio-info radio-inline">
                <input type="radio" id="radioc" value="INT" name="set" <?= @$kelompok_jabatan == "INT" ? "checked" : null ?>>
                <label for="radioc" class="tooltips" 
                    data-toggle="tooltip" data-placement="top" 
                    data-original-title="Data jabatan">Struktural</label>
            </div>
            <div class="radio radio-success radio-inline">
                <input type="radio" id="radiod" value="EXT" name="set" <?= @$kelompok_jabatan == "EXT" ? "checked" : null ?>>
                <label for="radiod" class="tooltips" 
                    data-toggle="tooltip" data-placement="top" 
                    data-original-title="Data jabatan">Non Struktural</label>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <?php echo form_dropdown('jabatan', null, null , 'class="form-control" id="jabatan" data-placeholder="- PILIH -"') ?>
        </div>
    </div>
</div>

  <div class="mb-3">
    {{-- <label for="jabatan_struktural" class="form-label">Jabatan Struktural</label> --}}
    <?php 
    //form_dropdown('struktural', @$jabatan_struktural, @$input['struktural_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') 
    ?>
  </div>
  <div class="mb-3">
    {{-- <label for="jabatan" class="form-label">Jabatan</label> --}}
    <?php 
    //form_dropdown('jabatan', $jabatan, $input['jabatan_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') 
    ?>
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
  @if($input['stts'] == '0')
  <a class="btn btn-success" href="{{ site_url('backend/user/activation/'. $input['id'] ) }}" >Aktivasi</a>
  @endif
</form>
@endsection

@section('js')
<script type="text/javascript" src="{{ base_url('node_modules') }}/select2/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).on('click', '.btnHapus', function(){

  });

  $(document).ready( function () {
    $('.chosen').chosen({
        width: "100%"
    });

    $(document).on('click', '[name="set"]', function () {
        if($(this).val() == "EXT"){
            select_pegawai("non_struktural");
            // alert('EXT')
          }

          if($(this).val() == "INT"){
            select_pegawai("struktural");
            // alert('INT')
          }
       
    });

    function select_pegawai(param){
        var act;
        
        if(param == "non_struktural"){
            act = 'backend/user/get_jabatan/non_struktural';
        }else if(param == "struktural"){
            act = 'backend/user/get_jabatan/struktural';
        }

        $('#jabatan').on(
          'select2:open', function() {
            document.querySelector('.select2-search__field').focus();
          }   
        ).select2({
        // $('#jabatan').select2({
        
        allowClear:true,
        placeholder: 'Ketik nama jabatan',
        minimumInputLength: 2,
        multiple: false,
        ajax: {
            url: '<?= base_url() ?>' + act,
            dataType: 'json',
            quietMillis: 100,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            cache: true
            },
        });
        $('#jabatan').select2('open');
        
        
    }

  });
</script>
@endsection