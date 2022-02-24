@extends('layouts.app')

@section('content')

<section id="login">
    <div class="container-fluid p-4">
        <h1>Log In</h1>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-5 m-4">
                    <div class="form-group mb-2">
                        <label for="email">E-mail</label><p>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="error">
                            {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>

                    <div class="form-group mb-2">
                        <label for="password">Password</label><p>
                        <input id="password" type="password" name="password" class="form-control" required>
                        @if ($errors->has('password'))
                            <span class="error">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>

                   
                </div>
                </div>
            </div>
            <div class="row-sm-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary me-2">Login</button>
                <a class="btn btn-outline-primary" href="{{ route('register') }}" role="button">Register</a>
            </div>
        </form>
    </div>
</section>
@endsection
