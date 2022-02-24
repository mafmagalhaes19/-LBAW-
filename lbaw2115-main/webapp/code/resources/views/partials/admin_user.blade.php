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
<h1>Reports</h1>
<div class="user-events info-div bg-light text-dark rounded">

    @if (count($reports) > 0)
        @foreach ($reports as $report)

            <div class=" m-2 p-2 border border-primary rounded">
                <div class="row">
                    <div class="col">
                        <span class="text-bold pl-1">
                            User
                            <a href="{{ url('/user/' . $report->userid) }}">
                                @php
                                    echo App\Http\Controllers\UserController::report_author($report->id)->username;
                                @endphp</a>
                            made a report in Event
                            <a href="{{ url('/event/' . $report->eventid) }}">
                                @php
                                    echo App\Http\Controllers\UserController::report_event($report->id)->eventname;
                                @endphp</a>
                            :
                        </span>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <a class="btn btn-danger" href="{{ url('/report/' . $report->id . '/delete') }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg></a>
                    </div>
                </div>

                <p class="text-bold m-2 p-2">{{ $report->descriptions }}</p>
            </div>
        @endforeach

    @else
        <h2 class="font-weight-bold pb-2 m-3">No reports found</h2>
    @endif
</div>
