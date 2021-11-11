<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Welcome to FiliPay Support Portal" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/icon" />
    <title>XMCIT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}" />
    <script defer src="{{ mix('dist/js/app.js') }}"></script>

</head>

<body>

    <div id="xerodesk-portal"></div>

    {{-- LaravelMix: For Development Only --}}
    @if (config('app.env') == 'local') <script src="http://localhost:35729/livereload.js"></script> @endif

    {{-- Jquery and Bootstrap Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha256-/ijcOLwFf26xEYAjW75FizKVo5tnTYiQddPZoLUHHZ8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>

    @if (config('app.env') == 'local')
        {{-- Unminified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.js" integrity="sha512-YXLGLsQBiwHPHLCAA9npZWhADUsHECjkZ71D1uzT2Hpop82/eLnmFb6b0jo8pK4T0Au0g2FETrRJNblF/46ZzQ==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.5.1/vue-router.js" integrity="sha512-mQKtM7fSv7pIQ6r4Jqe7c7K3QKEa1G+qNp0o9ohfGNeTT7pl85our+sPlSkzogl1oChZj75zhq9HGAj7Pf4Wjw==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.6.2/vuex.js" integrity="sha512-i48GtNrU5tVNKFkvIT3nArzgcIYGLxb0t6Ok+yu6yybHksvifmC+mmT2c3II7PZgUsA5sFnxROrkeM5Yt46g3A==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.js" integrity="sha512-ChO2LAXg+AJFABJ8me72N+/4Mkfj1MN6FgtsPY+XwRHMWchJFnkwqRxAqfpNxZy24BtoUB39GW/jp3MrSNKPag==" crossorigin="anonymous"></script>
    @else
        {{-- Minified Versions --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js" integrity="sha512-BKbSR+cfyxLdMAsE0naLReFSLg8/pjbgfxHh/k/kUC82Hy7r6HtR5hLhobaln2gcTvzkyyehrdREdjpsQwy2Jw==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.4.6/vue-router.min.js" integrity="sha512-WKm6zfjoCM1kbayG292NOP78iRCT9qholiYvcQRNngQ7rx2anfNpOsr5MAn/tVnbUxm2q62xG3koNy/YkCJaEg==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.5.1/vuex.min.js" integrity="sha512-n/iV5SyKXzLRbRczKU75fMgHO0A1DWJSWbK5llLNAqdcoxtUK3NfgfszYpjhvcEqS6nEXwu7gQ5bIkx6z8/lrA==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js" integrity="sha512-XVnzJolpkbYuMeISFQk6sQIkn3iYUbMX3f0STFUvT6f4+MZR6RJvlM5JFA2ritAN3hn+C0Bkckx2/+lCoJl3yg==" crossorigin="anonymous"></script>
    @endif


</body>

</html>