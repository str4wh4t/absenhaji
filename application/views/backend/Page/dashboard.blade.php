@extends('template.backend.layout')
@section('content')
<br>
<div class="row">
  <div class="col-lg-8 p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
          <h1 class="display-5 fw-bold pb-3">Absensi Satgas Haji</h1>
          <p class="col-md-8 fs-4">Aplikasi untuk manajemen data absensi satgas haji di Indonesia, silahkan anda langsung dapat melakukan absensi dengan tombol dibawah</p>
          <a class="btn btn-primary btn-lg" href="{{ site_url('backend/absen/scan') }}"><span class="bi-qr-code-scan"></span> Absen Sekarang</a>
        </div>
      </div>

          <div class="col-lg-4">
      <div class="card">
  <div class="card-header">
    User Info
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-4">Fullname</dt>
      <dd class="col-sm-8">{{ $session['user']->fullname }}</dd>

      <dt class="col-sm-4">Username</dt>
      <dd class="col-sm-8">{{ $session['user']->username }}</dd>

      <dt class="col-sm-4">Email</dt>
      <dd class="col-sm-8">{{ $session['user']->email }}</dd>
      <dt class="col-sm-4">Bidang</dt>
      <dd class="col-sm-8">nama bidang</dd>
      <dt class="col-sm-4">Instansi</dt>
      <dd class="col-sm-8">nama instansi</dd>
      <dt class="col-sm-4">Jabatan</dt>
      <dd class="col-sm-8">nama jabatan</dd>
    </dl>
  </div>
</div>
    
</div>
</div>
@endsection