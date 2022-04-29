@extends('layout')

@section('content')
    <div class="jumbotron">
        <ul class="navbar-nav">
        @foreach($events as $event)
            <li class="navbar-item">
                <p>{{!blank($event['title']) ? $event['title'] : '{Без названия}'}} - start: {{$event['start']}} end: {{$event['finish']}}</p>
                <a href="/attendance/{{$event['event_id']}}/report" class="nav-link">
                    <button type="button" class="btn btn-secondary">Report</button>
                </a>
            </li>
        @endforeach
        </ul>
    </div>
@endsection
