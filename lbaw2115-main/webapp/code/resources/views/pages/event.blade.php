@extends('layouts.app')

@section('title', 'Event')

@section('content')

    <section id="event">
        <div class="container-fluid p-4">
            @if (!is_null($popup_message))
                <div id="popup" class="popup-container">
                    <div class="popup">
                        <p class="popup-elems">{{$popup_message}}</p>
                        <button id="close" type="button" class="popup-elems btn-close"></button>
                    </div>
                </div>

                <script>
                    setup_popup_btn("close","popup");
                </script>
            @endif

            <div class="event-details pb-5">
                <div class="row">
                    <div class="col-sm-8 event-info">
                        <div class="row">
                            <div class="col-auto">
                                <h1 class="font-weight-bold">{{ $event->eventname }}
                                </h1>
                            </div>
                            <div class="col-auto mb-2">
                                <div class="dropdown dropend">
                                    <button class="btn btn-secondary dropdown-toggle pt-1" type="button"
                                        id="settings_dropdown" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-gear" viewBox="0 0 16 16">
                                            <path
                                                d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
                                            <path
                                                d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" />
                                        </svg> </button>
                                    @php
                                        $aux = true;
                                    @endphp
                                    <ul class="dropdown-menu" aria-labelledby="settings_dropdown">
                                        @foreach ($hosts as $host)
                                            @if ($host->id == Auth::user()->id || Auth::user()->isadmin)
                                                {{ $aux = false }}
                                                <li><a href="{{ url('/event/' . $event->id) . '/announcement' }}"
                                                        class="dropdown-item">Add
                                                        Announcement</a></li>
                                                <li class="dropdown-divider"></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/invite' }}"
                                                        class="dropdown-item">Invite
                                                        User(s)</a></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/addparticipants' }}"
                                                        class="dropdown-item">Add
                                                        Participant(s)</a></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/removeparticipants' }}"
                                                        class="dropdown-item">Remove
                                                        Participant(s)</a></li>
                                                <li class="dropdown-divider"></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/edit' }}"
                                                        class="dropdown-item">Edit
                                                        Event</a></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/cancel' }}"
                                                        class="dropdown-item">Cancel
                                                        Event</a></li>
                                                <li><a href="{{ url('/event/' . $event->id . '/delete') }}"
                                                        class="dropdown-item">Delete
                                                        Event</a></li>
                                                <li class="dropdown-divider"></li>

                                            @endif
                                        @break
                                        @endforeach
                                        @foreach ($participants as $participant)
                                            @if ($participant->id == Auth::user()->id)
                                                {{ $aux = false }}
                                                <li><a href="{{ url('/event/' . $event->id) . '/leave' }}"
                                                        class="dropdown-item">Leave Event
                                                    </a></li>
                                                <li class="dropdown-divider"></li>
                                                <li><a href="{{ url('/event/' . $event->id) . '/invite' }}"
                                                        class="dropdown-item">Invite
                                                        User(s)</a></li>
                                                <li class="dropdown-divider"></li>

                                            @endif
                                        @endforeach

                                        <li> <a href="{{ url('/event/' . $event->id) . '/report' }}"
                                                class="dropdown-item">Report Event</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-calendar-event text-center" viewBox="0 0 16 16">
                                    <path
                                        d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                </svg>
                            </div>
                            <div class="col-sm-auto my-auto">
                                <h5 class="my-auto"> {{ $event->startdate }} - {{ $event->enddate }}</h5>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                </svg>
                            </div>
                            <div class="col-sm-auto my-auto">
                                <h5 class="my-auto"> Duration: {{ $event->duration }}</h5>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-map" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" />
                                </svg>
                            </div>
                            <div class="col-sm-auto my-auto">
                                <h5 class="my-auto"> {{ $event->place }} </h5>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person-badge" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path
                                        d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z" />
                                </svg>
                            </div>
                            <div class="col-sm-auto my-auto">
                                <h5 class="my-auto"> Hosts:
                                    @foreach ($hosts as $host)
                                        <a class="m-2 .text-black link-black" href="{{ url('/user/' . $host->id) }}">
                                            {{ $host->username }}
                                        </a>
                                    @endforeach

                                </h5>
                            </div>
                        </div>
                        <div class="row pb-1">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-tag" viewBox="0 0 16 16">
                                    <path
                                        d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0z" />
                                    <path
                                        d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1zm0 5.586 7 7L13.586 9l-7-7H2v4.586z" />
                                </svg>
                            </div>
                            <div class="col">
                                <h5> {{ $tag->tagname }}</h5>
                            </div>
                        </div>
                        <div class="row"></div>

                        <div class="row pb-1">
                            <div class="col-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-info-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path
                                        d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                </svg>
                            </div>
                            <div class="col">
                                <h5> {{ $event->eventstate }}</h5>
                            </div>
                        </div>
                        <div class="row"></div>
                    </div>

                    <div class="col-sm-4 event-image">
                        <div class="row justify-content-center mb-2">
                            <img src="{{ $event->pictureurl }}" class="img-fluid rounded-start"
                                style="height: 150px; width: 100%; object-fit: cover;">
                        </div>
                        <div class="col-auto">
                            @foreach ($participants as $participant)
                                @if ($participant->id == Auth::user()->id)
                                    <div class="row-sm-auto text-center mb-2"><a class="btn btn-sm btn-outline-success"
                                            href="{{ url('/event/' . $event->id) . '/comment' }}">Add Comment</a></div>
                                @endif
                            @endforeach
                            @if ($aux)
                                <div class="row-sm-auto text-center mb-2"><a class="btn btn-sm btn-outline-success"
                                        href="{{ url('/event/' . $event->id) . '/join' }}">Join Event</a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs -->
            <ul class="nav nav-tabs justify-content-center">

                <li class="nav-item m-2 rounded">
                    <button class="nav-link rounded-top active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description-content" type="button" role="tab" aria-controls="announcements-content"
                        aria-selected="false">
                        <h3 class="m-2">Description</h3>
                    </button>
                </li>

                <li class="nav-item m-2 rounded">
                    <button class="nav-link text-light rounded-top" id="announcements-tab" data-bs-toggle="tab"
                        data-bs-target="#announcements-content" type="button" role="tab"
                        aria-controls="announcements-content" aria-selected="false">
                        <h3 class="m-2">Announcements</h3>
                    </button>
                </li>
                <li class="nav-item m-2 rounded">
                    <button class="nav-link text-light rounded-top" id="forum-tab" data-bs-toggle="tab"
                        data-bs-target="#forum-content" type="button" role="tab" aria-controls="forum-content"
                        aria-selected="false">
                        <h3 class="m-2">Forum</h3>
                    </button>
                </li>
                <li class="nav-item m-2 rounded">
                    <button class="nav-link text-light rounded-top" id="participants-tab" data-bs-toggle="tab"
                        data-bs-target="#participants-content" type="button" role="tab" aria-controls="participants-content"
                        aria-selected="false">
                        <h3 class="m-2">Participants</h3>
                    </button>
                </li>
            </ul>

            <!-- Tab contents -->
            <div class="tab-content text-dark rounded px-3 pt-3 pb-1 m-2" id="nav-tabContent">
                <div class="tab-pane fade active show overflow-auto" id="description-content" role="tabpanel"
                    aria-labelledby="description-tab">
                    <h3 class=mb-3>Description</h3>
                    {{ $event->event_description }}
                </div>

                <div class="tab-pane fade pb-3" id="announcements-content" role="tabpanel"
                    aria-labelledby="announcements-tab">
                    <h3>Announcements</h3>
                    @if (count($announcements) > 0)
                        @include('partials.announcements',$announcements)
                    @else
                        <p>
                            No announcements yet.
                        </p>
                    @endif
                </div>

                <div class="tab-pane fade pb-3" id="forum-content" role="tabpanel" aria-labelledby="forum-tab">
                    <h3>Forum</h3>
                    @if (count($polls) != 0)
                        @include('partials.poll', [ 'polls' => $polls, 'pollOptions' => $pollOptions])
                    @endif
                    @if (count($comments) != 0)
                        @include('partials.comments',$comments)
                    @else
                        <p>
                            No comments yet. Comments made by participants will appear here.
                        </p>
                    @endif
                </div>

                <div class="tab-pane fade pb-3" id="participants-content" role="tabpanel"
                    aria-labelledby="participants-tab">
                    <h3 class="mb-3">Participants</h3>
                    @if (count($participants) != 0)
                        @include('partials.participants',$participants)
                    @else
                        <p>
                            No one is participating in this event for now.
                        </p>
                    @endif
                </div>
            </div>
    </section>

@endsection
