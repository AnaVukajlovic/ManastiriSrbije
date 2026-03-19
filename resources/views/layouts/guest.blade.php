<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pravoslavni Svetionik') }}</title>

    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <style>
      html, body{
        margin:0;
        padding:0;
        min-height:100%;
        background:
          radial-gradient(1200px 700px at 20% 0%, rgba(197,162,74,.14) 0%, transparent 55%),
          radial-gradient(900px 520px at 90% 15%, rgba(255,255,255,.06) 0%, transparent 60%),
          linear-gradient(180deg, #120c0d, #181112);
        color: rgba(255,255,255,.92);
      }

      body{
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      }

      .guest-shell{
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:32px 16px;
      }

      .guest-box{
        width:min(520px, 100%);
        border-radius:24px;
        border:1px solid rgba(255,255,255,.10);
        background:
          radial-gradient(circle at top right, rgba(197,162,74,.09), transparent 24%),
          linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
        box-shadow:0 24px 60px rgba(0,0,0,.30);
        padding:28px 22px;
      }

      .guest-logo{
        display:flex;
        justify-content:center;
        margin-bottom:18px;
      }

      .guest-logo a{
        color:#f0d892;
        text-decoration:none;
        font-weight:900;
        font-size:22px;
      }
    </style>
</head>
<body>
    <div class="guest-shell">
        <div class="guest-box">
            <div class="guest-logo">
                <a href="/">Pravoslavni Svetionik</a>
            </div>
            {{ $slot }}
        </div>
    </div>
</body>
</html>