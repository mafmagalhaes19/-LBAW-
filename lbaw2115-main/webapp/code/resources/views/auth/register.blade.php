@extends('layouts.app')

@section('content')
<section id="login">
    <div class="container-fluid p-4">
        <h1>Register</h1>
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <div class="row justify-content-center">
                <div class="col-sm-5 m-4">
                    <div class="form-group mb-2">
                      <label for="name">Name</label><p>
                      <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                      @if ($errors->has('name'))
                        <span class="error">
                            {{ $errors->first('name') }}
                        </span>
                      @endif
                    </div>

                    <div class="form-group mb-2">
                      <label for="email">E-Mail Address</label><p>
                      <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                      @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                      @endif
                    </div>
                </div>

                <div class="col-sm-5 m-4">
                    <div class="form-group mb-2">
                      <label for="password">Password</label><p>
                      <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
                      @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group mb-2">
                      <label for="password-confirm">Confirm Password</label><p>
                      <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    </div>
                </div>
                </div>

                <div class="row-sm-2 d-flex justify-content-center">
                  <button type="submit button"  class="btn btn-primary me-2">
                    Register
                  </button>
                  <a class="btn btn-outline-primary ms" href="{{ route('login') }}">Login</a>
                </div>
              </form>
</section>
@endsection
