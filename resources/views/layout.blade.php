
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Michael Kovalchuk">
    <meta name="generator" content="handwritten">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MiniSend Test For Vue & Laravel</title>
    <link rel="canonical" href="#">

{{--    <!-- Bootstrap core CSS -->--}}
{{--    <link href="/css/bootstrap.min.css" rel="stylesheet">--}}
    <style>
        .container {
            padding: 100px 15px 0;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">MiniSend</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="/documentation">Documentation <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @if(isset($user) && $user)
            <form class="form-inline my-2 my-lg-0" method="post" action="{{route('logoff')}}">
                @csrf
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Log off, {{$user->name}}</button>
            </form>
        @endif
    </div>
</nav>

<main role="main" class="container">
    @yield('content')
</main><!-- /.container -->
<script src="/js/miniSend.app.js"><</script>
{{--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>--}}
{{--<script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script></body>--}}
</html>
