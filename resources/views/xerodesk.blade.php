<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Welcome to FiliPay Support Portal" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>XMCIT</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}" />
    <script defer src="{{ mix('dist/js/app.js') }}"></script>

</head>

<body>

    <div id="xerodesk-portal"></div>

    {{-- Jquery and Bootstrap Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha256-/ijcOLwFf26xEYAjW75FizKVo5tnTYiQddPZoLUHHZ8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>

    @if (config('app.env') == 'local')
        {{-- LaravelMix: For Development Only --}}
        <script src="http://localhost:35729/livereload.js"></script>

        {{-- Unminified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js" integrity="sha512-pSyYzOKCLD2xoGM1GwkeHbdXgMRVsSqQaaUoHskx/HF09POwvow2VfVEdARIYwdeFLbu+2FCOTRYuiyeGxXkEg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.5.3/vue-router.js" integrity="sha512-vcS/e3/5XPGEtUrK3Dk5haoBdlU2/oO3o5uJQuLMzMILwxyeRB61n7SkjgjQ6GwJrwKTDbgd5KjyjIZ2sIDZrg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.2/vuex.js" integrity="sha512-i48GtNrU5tVNKFkvIT3nArzgcIYGLxb0t6Ok+yu6yybHksvifmC+mmT2c3II7PZgUsA5sFnxROrkeM5Yt46g3A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.js" integrity="sha512-ChO2LAXg+AJFABJ8me72N+/4Mkfj1MN6FgtsPY+XwRHMWchJFnkwqRxAqfpNxZy24BtoUB39GW/jp3MrSNKPag==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @else
        {{-- Minified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js" integrity="sha512-XdUZ5nrNkVySQBnnM5vzDqHai823Spoq1W3pJoQwomQja+o4Nw0Ew1ppxo5bhF2vMug6sfibhKWcNJsG8Vj9tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.5.3/vue-router.min.js" integrity="sha512-/QcUyef9r0LQzC+WtTNs+gJNacB+x20p1ZaPKSldF9Vu3m+uOmz0bBpPpsmHncv1ehuOCwnMXYydvl1KkW7Xow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.2/vuex.min.js" integrity="sha512-Tkxwo8dZEZTmje5QT9uodCqe2XGbZdBXU8uC4nskBt0kwR99Anzkz8JCSMByfoqjLTHcTuIB8fsmED3b9Ljp3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js" integrity="sha512-XVnzJolpkbYuMeISFQk6sQIkn3iYUbMX3f0STFUvT6f4+MZR6RJvlM5JFA2ritAN3hn+C0Bkckx2/+lCoJl3yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endif

</body>

</html>