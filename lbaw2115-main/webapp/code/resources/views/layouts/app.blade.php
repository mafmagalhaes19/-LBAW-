<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}">
</script>
  </head>
  <body>
    <main>
    <header>
        <nav class="navbar navbar-expand-lg bg-primary navbar-dark nav-justified">
          <div class="container-fluid">
            <a class="ms-4 navbar-brand text-nowrap" href="{{ url('/') }}">Event Pointer</a>

            <form method="GET" class="d-inline-flex w-25" action="{{ route('browse.search') }}">
              <input id="search-input" class="form-control form-control-sm me-sm-2" type="text" name="search_query" value="{{ old('search_query') }}" required placeholder="Search">
              <button class="btn btn-secondary btn-sm my-2 my-sm-0" type="submit">Search</button>
            </form>

            <div class="justify-content-end" id="navbarColor01">
              <ul class="navbar-nav text-nowrap">
                @if (Auth::check())
                <!-- <li class="nav-item nav-link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                  </svg>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/user/'.@Auth::user()->id.'/createevent') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                      <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                  </a>
                </li>               
                @endif
                <li class="nav-item">
                  <a href="{{ url('/browse') }}"><h4 class="nav-link">Browse</h4></a>
                </li>
                @if (Auth::check())
                <li class="nav-item">
                  <a href="{{ url('/user/'.@Auth::user()->id) }}"><h4 class="nav-link">My Page</h4></a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('/logout') }}"><h4 class="nav-link">Logout</h4></a>
                </li>
                @else
                <li class="nav-item">
                  <a href="{{ url('/login') }}"><h4 class="nav-link">Log In</h4></a>
                </li>
                @endif
                <!-- <li class="nav-item">
                  
                </li> -->
              </ul>
            </div>

          </div>
        </nav>
      </header>


      <section class="m-3" id="content">
        @yield('content')
      </section>

      <footer>
        <div class="container-fluid mt-4">
          <div class="row">
            <div class="col-sm-auto">
              <a class="btn btn-link" href="{{ url('/about') }}">About</a>
            </div>
            <div class="col-sm">
              <a class="btn btn-link" href="{{ url('/faq') }}">FAQ</a>
            </div>
            <div class="col-sm-auto">
              <a class="btn btn-link" href="{{ url('/contacts') }}">Contacts</a>
            </div>
          </div>
        </div>
      </footer>
    </main>
  </body>
</html>
