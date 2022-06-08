@extends('template.backend.layout')
@section('content')
<h2 class="title">{{ $title }}</h2>
<hr>
<form class="col-6" method="post" action="{{ site_url($action) }}">
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
  <div class="mb-3">
    <label for="fullname" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $input['fullname'] }}">
  </div>
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" value="{{ $input['username'] }}">
  </div>
  <div class="mb-3">
    <label for="jabatan_struktural" class="form-label">Jabatan Struktural</label>
    <?= form_dropdown('struktural', @$jabatan_struktural, @$input['struktural_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  <div class="mb-3">
    <label for="bidang" class="form-label">Bidang</label>
    <?= form_dropdown('bidang', @$bidang, @$input['bidang_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  <div class="mb-3">
    <label for="instansi" class="form-label">Instansi</label>
    <?= form_dropdown('instansi', @$instansi, @$input['instansi_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
  </div>
  <div class="mb-3">
    <label for="jabatan" class="form-label">Jabatan</label>
    <?= form_dropdown('jabatan', @$jabatan, @$input['jabatan_id'] , 'class="form-select chosen" data-placeholder="- PILIH -"') ?>
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
</form>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', '.btnHapus', function(){

  });

  $(document).ready( function () {
    $('.chosen').chosen({
        width: "100%"
    });
  });
</script>
@endsection