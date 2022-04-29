@extends('layout')

@section('content')
    <div class="jumbotron">
        <ul class="navbar-nav">
            @foreach($users as $user)
                <li class="navbar-item">
                    <h3>{{$user['full_name']}}</h3>
                    <p>Email - {{$user['email']}}</p>
                    <p>Join Time - {{$user['join']}}</p>
                    <p>Leave Time - {{$user['leave']}}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
