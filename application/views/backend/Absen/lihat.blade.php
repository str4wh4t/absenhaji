@extends('template.backend.layout')

@section('css')
<style type="text/css">
#kode_absen{
  align:center
}
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
    <label for="expired_at" style="width: 100%;" class="form-label">Expired At</label>
    <div class="alert alert-dark" style="background: none;">{{ $absen->expired_at }}</div>
  </div>
  <button type="button" class="btn btn-outline-info" id="printAbsen"  ><span class="bi-printer-fill"></span> Cetak</button>
  <a class="btn btn-outline-danger" href="{{ site_url('backend/absen') }}" >Kembali</a>
</form>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', '#printAbsen', function(){
     printJS('printArea', 'html');
  });

  $(document).ready( function () {
    //
  });
</script>
@endsection