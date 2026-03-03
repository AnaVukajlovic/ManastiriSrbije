<header class="topbar">
    <div class="topbar__left">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand__logo">MS</span>
            <span class="brand__name">Manastiri Srbije</span>
        </a>
    </div>

    <div class="topbar__center">
        <form class="search" action="{{ url('/manastiri') }}" method="GET">
            <input class="search__input" type="text" name="q" placeholder="Pretraga manastira, mesta, eparhije..." />
            <button class="search__btn" type="submit">🔍</button>
        </form>
    </div>

    <div class="topbar__right">
        <a class="toplink" href="{{ url('/') }}">Početna</a>

       <a class="toplink" href="#">Login</a>
    </div>
</header>
