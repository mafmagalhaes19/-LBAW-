@extends('layouts.app')

@section('title', 'editevent')

@section('content')

    <section id="edit_event">
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
        <h1 class="p-2"> <a role="button" href="{{ url('/event/' . $event->id) }}" class="btn w-auto mx-3"><svg
                    xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                    class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg></a>Edit Event
        </h1>
        <div class="row gap-5">
            <div class="h-50 col-md-2 md-2 ms-3 border rounded p-3 border-primary align-center">
                <h4 class="mb-3">Current configuration:</h4>
                <p> <b>Name:</b> {{ $event->eventname }}</p>
                <p> <b>Start Date:</b> {{ $event->startdate }}</p>
                <p> <b>End Date:</b> {{ $event->enddate }}</p>
                <p> <b>Place:</b> {{ $event->place }}</p>
                <p> <b>Even State:</b> {{ $event->eventstate }}</p>
                <p> <b>Private:</b>
                    @if ($event->isprivate)
                        Yes
                    @else
                        No
                    @endif
                </p>
            </div>


            <div class="col-md-1 w-75 border rounded py-2 bg-light text-dark align-center border-primary">
                <div class="mb-3 m-2">
                    <h3 class="m-1">Please enter new event details:</h3>
                </div>
                <div class="col m-2">
                    <form method="POST" action="{{ route('event.update', $event->id) }}">
                        {{ csrf_field() }}

                        <div class="form-group mb-2">
                            <label for="eventname">Event Name</label>
                            <input type="text" class="form-control" id="eventname" name="eventname"
                                value={{ $event->eventname }}>
                        </div>

                        <div class="form-group mb-2">
                            <label for="event_description">Event Description</label>
                            <input type="text" class="form-control" id="event_description" name="event_description"
                                value={{ $event->event_description }}>
                        </div>


                        <div class="form-group mb-2">
                            <label for="place">Place</label>
                            <input type="text" class="form-control" id="place" name="place" value={{ $event->place }}>
                        </div>

                        <div class="form-group mb-2">
                            <label for="startdate"> Start-Date</label>
                            <input id="startdate" class="form-control" type="date" name="startdate">
                        </div>

                        <div class="form-group mb-2">
                            <label for="enddate"> End-Date</label>
                            <input id="enddate" class="form-control" type="date" name="enddate">
                        </div>


                        <div class="form-group mb-2">
                            <label>State</label>
                            <select class="form-control" id="eventstate" name="eventstate">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Finished">Finished</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Ongoing" selected>Ongoing</option>


                            </select>
                        </div>

                        <div class="form-group">
                            <label>Private</label>
                            <select class="form-control" id="isprivate" name="isprivate">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="pictureurl">Picture URL</label>
                            <input type="text" class="form-control" id="pictureurl" name="pictureurl"
                                value={{ $event->pictureurl }}>
                        </div>

                        <div class="row mt-4 me-1 flex-row-reverse">
                            <button type="submit" class="btn btn-success w-auto">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>


    </section>

@endsection
