@extends('template.backend.layout')

@section('css')
<style type="text/css">
@media print {
#printArea{
     display:flex;
     justify-content:center;
     align-items:center;
     height:100%;
     }
    html, body{
      height:100%;
      width:100%;
    }
}
</style>
@endsection

@section('content')
<h2 class="title">Lihat Absen</h2>
<hr>
<form class="col-6" method="post" action="">
  <div class="mb-3">
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
<div id="printArea" style="display: none;">
  <table style="width: 100%;">
    <tr>
      <td style="text-align: center; font-size: 32px;">
        Qrcode Absen
      </td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <img id="kode_absen" src="{{ $qrcode['src'] }}" />
      </td>
    </tr>
    <tr>
      <td style="text-align: center; font-size: 32px;">
        &nbsp;
      </td>
    </tr>
    <tr>
      <td style="text-align: center; font-size: 32px;">
        Expired At
      </td>
    </tr>
    <tr>
      <td style="text-align: center; font-size: 32px;">
        {{ $absen->expired_at }}
      </td>
    </tr>
  </table>
</div>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', '#printAbsen', function(){
      // printJS('printArea', 'html');
      // let restorepage = document.body.innerHTML;
      // let printcontent = document.getElementById('printArea').innerHTML;
      // document.body.innerHTML = printcontent;
      // window.print();

      let printContent = document.getElementById('printArea');
      let WinPrint = window.open('', '', 'width=900,height=650');
      WinPrint.document.write(printContent.innerHTML);
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
  });

  $(document).ready( function () {
    //
  });
</script>
@endsection