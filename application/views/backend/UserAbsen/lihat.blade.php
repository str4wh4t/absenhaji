@extends('template.backend.layout')

@section('css')
<style type="text/css">

</style>
@endsection

@section('content')
<h2 class="title">Lihat Absen</h2>
<hr>
<form class="col-6" method="post" action="">
  <div class="mb-3" id="printArea">
    <label for="kode_absen" style="width: 100%;" class="form-label">Kode Absen</label>
    <br>
    <img id="kode_absen" src="{{ $qrcode['src'] }}" />
    <br>
    <label for="expired_at" style="width: 100%;" class="form-label">Waktu Absen</label>
    <div class="alert alert-dark" style="background: none;">{{ $user_absen->created_at }}</div>
  </div>
  <a class="btn btn-outline-danger" href="{{ site_url('backend/absen/riwayat') }}" >Kembali</a>
</form>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', '#printAbsen', function(){
    //
  });

  $(document).ready( function () {
    //
  });
</script>
@endsection