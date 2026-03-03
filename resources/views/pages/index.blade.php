@extends('layouts.app')

@section('title', 'Manastiri')

@section('content')
    <h1>Manastiri</h1>

    <div class="toolbar">
        <select id="filter-eparchy">
            <option value="">Eparhija (sve)</option>
        </select>

        <select id="filter-century">
            <option value="">Vek (sve)</option>
        </select>

        <input id="q" type="search" placeholder="Pretraži manastire..." />
    </div>

    <div id="grid" class="grid"></div>

    <script>
        (async function(){
            const res = await fetch('/api/monasteries');
            const data = await res.json();

            const grid = document.getElementById('grid');
            grid.innerHTML = (data.data ?? data).map(m => `
                <a class="card" href="/manastiri/${m.slug}">
                    <div class="thumb" style="background-image:url('${m.cover_image ?? ''}')"></div>
                    <div class="card-body">
                        <div class="card-title">${m.name ?? m.naziv ?? 'Manastir'}</div>
                        <div class="card-sub">${m.location ?? ''}</div>
                    </div>
                </a>
            `).join('');
        })();
    </script>
@endsection
