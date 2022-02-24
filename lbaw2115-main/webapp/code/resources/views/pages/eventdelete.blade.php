@extends('layouts.app')

@section('title', 'Delete Event')

@section('content')

<section >
    <form method="GET" action="{{route('event.delete')}}">
    </form>
    
</section>

@endsection