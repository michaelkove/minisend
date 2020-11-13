@extends('layout')
@section('content')
    <div id="miniSendApp">
        <mini-send-app
            :userid="@json($user->id)"
        ></mini-send-app>
    </div>
@endsection
