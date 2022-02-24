@extends('layouts.app')

@section('title', 'Browse')

@section('content')

    <section id="browse">
        @if (!is_null($popup_message))
            <div id="popup" class="popup-container">
                <div class="popup">
                    <p class="popup-elems">{{ $popup_message }}</p>
                    <button id="close" type="button" class="popup-elems btn-close"></button>
                </div>
            </div>

            <script>
                setup_popup_btn("close", "popup");
            </script>
        @endif
        <h1 class="mb-3">Browse</h1>
        <div class="row gap-5">
            <div class="h-50 col-md-2 ms-3 border border-primary rounded p-3 bg-light text-dark align-center">
                <h2 class="m-1">Search:</h2>
                <form method="GET" class="form-group" action="{{ route('browse.search') }}">
                    <input id="search-input" class="mb-2 form-control ms-2 w-75 input-sm p-1" type="text" name="search_query"
                        value="{{ old('search_query') }}" autofocus placeholder="Search for events..">

                    <h3 class="mx-1 mt-3">Event Filters:</h3>

                    <h4 class="m-1 mx-3">State:</h4>
                    <select id="state-select" name="event_state" class="form-select form-select-sm ms-2 mb-3 w-75" multiple
                        aria-label=" multiple select example">
                        <option selected>All</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Canceled">Canceled</option>
                        <option value="Finished">Finished</option>
                    </select>

                    <h4 class="m-1 mx-3">Tag:</h4>
                    <select id="state-select" name="event_tag" class="form-select form-select-sm ms-2 mb-3 w-75" multiple
                        aria-label=" multiple select example">
                        <option selected>All</option>
                        <option value="1">Music </option>
                        <option value="2">Sports</option>
                        <option value="3">Movies and TV Shows </option>
                        <option value="4">Arts and leisure </option>
                        <option value="5">Programming </option>
                        <option value="6">Lifestyle</option>
                        <option value="7">Gaming</option>
                        <option value="8"> Tech</option>
                        <option value="9">Streaming </option>
                    </select>

                    <input type="submit" value="Search" class="btn btn btn-outline-success p-1 mb-2 w-100">

                </form>

                <!-- orderevents-->
                <h3 class="m-1 mt-2">Event Ordering:</h3>
                <div>
                    <div>
                        <h5 class="m-2 mb-1 mx-3">Start Date:</h5>
                        <div class="text-center btn btn-secondary m-2 me-3 mt-0 ps-2 pe-2 pt-1 pb-1"><a
                                class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'sdate-asc']) }}""><svg xmlns="
                                http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-arrow-up-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z" />
                                </svg>
                            </a></div>
                        <div class="text-center btn btn-secondary m-2 mt-0 ps-2 pe-2 pt-1 pb-1">
                            <a class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'sdate-desc']) }}"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                </svg></a>
                        </div>
                    </div>
                    <div>
                        <h5 class="m-2 mb-1 mx-3">End Date:</h5>
                        <div class="text-center btn btn-secondary m-2 me-3 mt-0 ps-2 pe-2 pt-1 pb-1"><a
                                class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'edate-asc']) }}"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-arrow-up-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z" />
                                </svg></a></div>
                        <div class="text-center btn btn-secondary m-2 mt-0 ps-2 pe-2 pt-1 pb-1"><a class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'edate-desc']) }}"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                </svg></a></div>
                    </div>


                    <div>
                        <h5 class="m-2 mb-1 mx-3">Duration:</h5>
                        <div class="text-center btn btn-secondary m-2 me-3 mt-0 ps-2 pe-2 pt-1 pb-1"><a
                                class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'dur-asc']) }}"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-arrow-up-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z" />
                                </svg></a></div>
                        <div class="text-center btn btn-secondary m-2 mt-0 ps-2 pe-2 pt-1 pb-1"><a class="sort-buttons"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'dur-desc']) }}"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                </svg></a></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 w-75 text-dark align-center">
                <div class="row p-3 bg-light border border-primary rounded mb-3">
                    <div class="mb-3">
                        <h2 class="m-1">Events:</h2>
                    </div>
                    <div class="col" id="browse-event">
                        @if (count($events) > 0)
                            @foreach ($events as $event)
                                @if (Auth::check() && !Auth::user()->isadmin)
                                    @if ($event->isprivate == false)
                                        @include('partials.browse_events')
                                    @endif
                                @else

                                    @include('partials.browse_events')
                                @endif
                            @endforeach
                        @else
                            <h5>No results were found for the specified query and filters.</h5>
                        @endif
                    </div>
                </div>

                    <div class="row p-3 bg-light border border-primary rounded mb-3">
                        <div class="mb-3">
                            <h2 class="m-1">Users:</h2>
                        </div>
                        <div class="col" id="browse-event">
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    @if (!$user->isadmin)
                                        @include('partials.browse_users')
                                    @endif
                                @endforeach
                            @else
                                <h5>No results were found for the specified query and filters.</h5>
                            @endif
                    </div>
                
                <div>
                
                </div>
            </div>
    </section>

@endsection
