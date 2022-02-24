@extends('layouts.app')

@section('title', 'inviteuser')

@section('content')

    <section id="add_participants">
        <h1 class="p-2"> <a role="button" href="{{ url('/event/' . $event->id) }}" class="btn w-auto mx-3"><svg
                    xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                    class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg></a>Invite Users
        </h1>
        <form method="GET" class="d-inline-flex w-75" action="{{ route('invite.show', $event) }}">
            <input id="search-input" class="form-control form-control-sm me-sm-2" type="text" name="search_query"
                value="{{ old('search_query') }}" required placeholder="Search">
            <button class="btn btn-secondary btn-sm my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div class="col m-4" id="browse-user">
            @if (count($users) > 0)
                <div class="col-md-1 w-75 border rounded py-2 bg-light text-dark align-center border-primary">
                    <div class="mb-3 m-2">
                        <h3 class="m-1 p-1">Please choose users you want to invite:</h3>
                    </div>
                    <div class="col m-2 p-3">

                        @foreach ($users as $user)
                            @if (!$user->isadmin)
                                <div class=" p-2 m-3 row border rounded border-primary">
                                    <div class="col m-auto">
                                        <span class="text-bold pl-1">
                                            {{ $user->username }}
                                        </span>
                                    </div>

                                    <div class="col d-flex justify-content-end">
                                        <a class="btn btn-success"
                                            href="{{ url('/event/' . $event->id . '/invite/' . $user->id) }}"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg></a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>


            @else
                <h5>No results were found for the specified query and filters.</h5>
            @endif
        </div>
    </section>

@endsection
