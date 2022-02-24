@extends('layouts.app')

@section('title', 'Delete User')

@section('content')

<section >
    <form method="GET" action="{{route('user.delete')}}">
    </form>
    
</section>

@endsection