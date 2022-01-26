<!DOCTYPE html>
<html lang="en">

<head>

    <title>Xerodesk - Widget Frame</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url($path . 'main.css?time=' . time()) }}" />
    <script defer src="{{ url($path . 'main.js?time=' . time()) }}"></script>

</head>

<body>

    <div id="xerodesk"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha256-/ijcOLwFf26xEYAjW75FizKVo5tnTYiQddPZoLUHHZ8=" crossorigin="anonymous"></script>

    @if (config('app.env') == 'local')
        {{-- LaravelMix: For Development Only --}}
        <script src="http://localhost:35729/livereload.js"></script>

        {{-- Unminified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js" integrity="sha512-pSyYzOKCLD2xoGM1GwkeHbdXgMRVsSqQaaUoHskx/HF09POwvow2VfVEdARIYwdeFLbu+2FCOTRYuiyeGxXkEg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.2/vuex.js" integrity="sha512-i48GtNrU5tVNKFkvIT3nArzgcIYGLxb0t6Ok+yu6yybHksvifmC+mmT2c3II7PZgUsA5sFnxROrkeM5Yt46g3A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.js" integrity="sha512-ChO2LAXg+AJFABJ8me72N+/4Mkfj1MN6FgtsPY+XwRHMWchJFnkwqRxAqfpNxZy24BtoUB39GW/jp3MrSNKPag==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/vue-popperjs@2.3.0/dist/vue-popper.js"></script>
    @else
        {{-- Minified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js" integrity="sha512-XdUZ5nrNkVySQBnnM5vzDqHai823Spoq1W3pJoQwomQja+o4Nw0Ew1ppxo5bhF2vMug6sfibhKWcNJsG8Vj9tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.2/vuex.min.js" integrity="sha512-Tkxwo8dZEZTmje5QT9uodCqe2XGbZdBXU8uC4nskBt0kwR99Anzkz8JCSMByfoqjLTHcTuIB8fsmED3b9Ljp3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js" integrity="sha512-XVnzJolpkbYuMeISFQk6sQIkn3iYUbMX3f0STFUvT6f4+MZR6RJvlM5JFA2ritAN3hn+C0Bkckx2/+lCoJl3yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/vue-popperjs@2.3.0/dist/vue-popper.min.js"></script>
    @endif

</body>

</html>