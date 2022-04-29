@extends('layout')

@section('content')
    <div class="jumbotron">
        @if(isset($userName))
            <h1>Welcome {{ $userName }}!</h1>
            <p>Use the navigation bar at the top of the page to get started.</p>
        @else
            <a href="/signin" class="btn btn-primary btn-large">Click here to sign in</a>
        @endif
    </div>
@endsection
