@extends('template.auth.layout')

@section('content')
  <form method="post" action="{{ site_url('login') }}">
    <!-- <img class="mb-4" src="/docs/5.2/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
    <h1 class="h3 mb-3 fw-normal">SATGAS HAJI</h1>

    @if($login_salah != '')
    <div class="alert alert-danger" role="alert">
        {{ $login_salah }}
    </div>
    @endif

    <div class="form-floating">
      <input type="text" class="form-control" id="{{ $login_with }}" name="{{ $login_with }}" placeholder="">
      <label for="{{ $login_with }}">{{ ucwords($login_with) }}</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="password" name="password" placeholder="">
      <label for="password">Password</label>
    </div>

    <!-- <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div> -->
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <a href="{{ site_url('registration') }}" class="w-100 btn btn-lg btn-danger mt-1">Daftar</a>
    <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
  </form>
@endsection
