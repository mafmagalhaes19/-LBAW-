@extends('layouts.app')

@section('title', 'Cancel Event')

@section('content')

<section >
    <form method="GET" action="{{route('event.cancel')}}">
    </form>
    
</section>

@endsection