@extends('layouts.app')

@section('title', 'report_event')

@section('content')

    <section id="report_event">
        <h1> <a role="button" href="{{ url('/event/' . $event_id) }}" class="btn w-auto mx-3"><svg
                    xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                    class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg></a>Report Event
        </h1>
        <form id="report_form" method="POST" action="{{ route('event.report', ['event_id' => $event_id]) }}">
            {{ csrf_field() }}
            <div class="form-group mt-3">
                <label for="report">Please write your message in this box...</label>
                <textarea id="report" form="report_form" name="report_message" class="form-control"
                    aria-label="With textarea" required></textarea>
            </div>
            <div class="text-center m-3"><button type="submit" class="btn btn-danger">Send</button></div>

        </form>

    </section>

@endsection
