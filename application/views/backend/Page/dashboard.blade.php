@extends('template.backend.layout')
@section('content')
<br>
<div class="row">
  <div class="col-lg-8 p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
          <h1 class="display-5 fw-bold pb-3">Aplikasi Absensi Petugas Haji</h1>
          <p class="col-md-8 fs-4">Aplikasi untuk manajemen data absensi petugas haji di Indonesia, silahkan anda langsung dapat melakukan absensi dengan tombol dibawah</p>
          <a class="btn btn-primary btn-lg" href="{{ site_url('backend/absen/scan') }}"><span class="bi-qr-code-scan"></span> Absen Sekarang</a>
        </div>
      </div>

          <div class="col-lg-4">
      <div class="card">
  <div class="card-header">
    Informasi
  </div>
  <div class="card-body">
    <h6 class="card-title">Fullname</h6>
        <p class="card-text text-end">{{ $session['user']->fullname }}</p>
    <h6 class="card-title">Username</h6>
        <p class="card-text text-end">{{ $session['user']->username }}</p>
    <h6 class="card-title">Email</h6>
    <p class="card-text text-end">{{ $session['user']->email }}</p>
  </div>
</div>
    </div>
</div>
@endsection