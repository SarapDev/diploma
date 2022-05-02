@extends('layout')

@section('content')
    <div class="jumbotron">
        <h1>{{ $eventReports['event']['title'] !== '' ? $eventReports['event']['title'] : 'Нет названия' }}</h1>
        <h3>start - {{$eventReports['event']['start'] }} | finish - {{$eventReports['event']['finish'] }}</h3>
        <br>
        <ul class="navbar-nav">
            @foreach($eventReports['report'] as $user)
                <li class="navbar-item">
                    <h3>{{$user['full_name']}}</h3>
                    <p>Email - {{$user['email']}}</p>
                    <p>Join Time - {{$user['join']}}</p>
                    <p>Leave Time - {{$user['leave']}}</p>
                @if($user['is_leaver'])
                    <div class="alert alert-danger" role="alert">
                        LEAVE EARLY
                    </div>
                @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
