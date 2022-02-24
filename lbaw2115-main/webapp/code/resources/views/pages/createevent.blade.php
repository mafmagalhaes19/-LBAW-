@extends('layouts.app')

@section('title', 'createevent')

@section('content')

    <section id="createevent">
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
        <h1 class="m-4">Create Event</h1>

        <div class="col-md-1 w-75 border rounded py-2 bg-light text-dark align-center border-primary m-5">
            <div class="mb-3 m-2">
                <h3 class="m-1">Please enter the event details:</h3>
            </div>
            <div class="col m-2">
                <form method="POST" action="{{ route('event.create', Auth::user()->id) }}">
                    {{ csrf_field() }}

                    <div class="form-group mb-2">
                        <label for="eventname">Event Name</label>
                        <input required type="text" class="form-control" id="eventname" name="eventname">
                    </div>

                    <div class="form-group mb-2">
                        <label for="event_description">Event Description</label>
                        <input required type="text" class="form-control" id="event_description" name="event_description">
                    </div>

                    <div class="form-group mb-2">
                        <label>Event Tag</label>
                        <select class="form-control" id="tagid" name="tagid">
                            <option value="1" selected>Music</option>
                            <option value="2">Sports</option>
                            <option value="3">Movies and TV Shows</option>
                            <option value="4">Arts and Leisure</option>
                            <option value="5">Programming</option>
                            <option value="6">Lifestyle</option>
                            <option value="7">Gaming</option>
                            <option value="8">Tech</option>
                            <option value="9">Streaming</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="place">Place</label>
                        <input required type="text" class="form-control" id="place" name="place">
                    </div>

                    <div class="form-group mb-2">
                        <label for="startdate"> Start-Date</label>
                        <input required id="startdate" class="form-control input_box" type="date" name="startdate">
                    </div>

                    <div class="form-group mb-2">
                        <label for="enddate"> End-Date</label>
                        <input required id="enddate" class="form-control input_box" type="date" name="enddate">
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
                            <option value="true">Yes</option>
                            <option value="false" selected>No</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="pictureurl">Picture URL</label>
                        <input required type="text" class="form-control" id="pictureurl" name="pictureurl">
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
