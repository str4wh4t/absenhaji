@extends('template.auth.layout')

@section('content')
  <form method="post" action="{{ site_url('registration') }}">
    <!-- <img class="mb-4" src="/docs/5.2/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
    <h1 class="h3 mb-3 fw-normal">REGISTRASI</h1>

    @if(!empty($list_errors))
    <div class="alert alert-danger fs-6" role="alert">
        <ul class="registration_error">
        @foreach($list_errors as $err)
            {!! $err !!}
        @endforeach
        </ul>
    </div>
    @endif

    @if($registration_sukses != '')
    <div class="alert alert-success" role="alert">
        {{ $registration_sukses }}
    </div>
    @endif
    <div class="form-floating">
      <input type="text" class="form-control registration" id="fullname" name="fullname" value="{{ $user_input['fullname'] }}" placeholder="">
      <label for="fullname">Nama Lengkap</label>
    </div>
    <div class="form-floating">
      <input type="text" class="form-control registration" id="username" name="username" value="{{ $user_input['username'] }}" placeholder="">
      <label for="username">Username</label>
    </div>
     <div class="form-floating">
      <input type="text" class="form-control registration" id="email" name="email" value="{{ $user_input['email'] }}" placeholder="">
      <label for="email">Email</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control registration mb-1" id="password" name="password" value="{{ $user_input['password'] }}" placeholder="">
      <label for="password">Password</label>
    </div>
    <div class="form-floating">
    <div class="g-recaptcha mb-1" data-sitekey="{{ $_ENV['GCAPTCHA_V2_SITE_KEY'] }}"></div>
    </div>
    <!-- <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div> -->
    <button class="w-100 btn btn-lg btn-danger" type="submit">Daftar</button>
    <a href="{{ site_url('login') }}" class="w-100 btn btn-lg btn-primary mt-1">Sign in</a>
    <p class="mt-5 mb-3 text-muted">&copy; 2022 - <?= date('Y') ?></p>
  </form>
@endsection

@section('js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    

</script>
@endsection
