@extends('layouts.app')

@section('content')
    <div class="editUser p-4">
        <form method="POST" action="{{ route('user.update', $user->id) }}">
            {{ csrf_field() }}

            <div class="form-group">
                <h1 class="p-2"> <a role="button" href="{{ url('/user/' . $user->id) }}"
                        class="btn w-auto mx-3"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                        </svg></a>Edit Account
                </h1> <br>
                <input type="hidden" class="form-control" id="id" name="id" value="{{ $user->id }}">
                <div class="row justify-content-center">
                    <div class="col-5 m-4">
                        <div class="form-group mb-2">
                            <label for="name">Name</label>
                            <p>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ $user->name }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="address">Email</label>
                            <p>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="{{ $user->email }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="description">Description</label>
                            <p>
                                <input type="description" class="form-control" id="description" name="description"
                                    placeholder="Description">
                        </div>
                    </div>
                    <div class="col-5 m-4">
                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <p>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password">
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Confirm Password</label>
                            <p>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm Password">
                        </div>
                        <div class="form-group mb-2">
                            <label for="profilePictureUrl">Profile Picture (URL)</label>
                            <p>
                                <input type="profilepictureurl" class="form-control" id="profilepictureurl"
                                    name="profilepictureurl" placeholder="Profile Picture URL">
                        </div>

                    </div>
                </div>
            </div>
            <br>
            <div class="d-flex justify-content-end row-sm-1 m-2 p-3">
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>

        </form>

    </div>
@endsection
