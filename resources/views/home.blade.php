@extends('layout')
@section('content')
    <div class="row">
        <div class="col-3 mx-auto">
            <h6>Please Pick User To "Login"</h6>
            <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item">
                    {{$user->name}}
                    <div class="badge badge-pill">
                        <form action="{{route('login')}}" method="post" class="form-inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-outline-primary">
                                LOGIN
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box-arrow-in-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"></path>
                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
    </div>
@endsection
