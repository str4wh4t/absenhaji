@extends('template.backend.layout')
@section('content')
<h2 class="title">Tambah Absen</h2>
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
    <label for="kode_absen" class="form-label">Kode Absen</label>
    <br>
    <img id="kode_absen" src="{{ $qrcode['src'] }}" />
    <input type="hidden" value="{{ $qrcode['kode_absen'] }}" name="kode_absen">
    <br>
    <label for="expired_at" class="form-label">Expired At</label>
    <input type="text" class="form-control datetimepicker" id="expired_at" name="expired_at" value="{{ $input['expired_at'] }}">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="button" id="regenerateCode" class="btn btn-success">Regenerate</button>
  <a class="btn btn-outline-danger" href="{{ site_url('backend/absen') }}" >Kembali</a>
</form>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', '#regenerateCode', function(){
    $.post('{{ site_url("backend/absen/generate") }}', {user: USERNAME}, function(data){
        $('#kode_absen').attr("src", data.src);
        $('input[name="kode_absen"]').val(data.kode_absen);
    });
  });

  $(document).ready( function () {
    $('.datetimepicker').datetimepicker();
  });
</script>
@endsection