@php
  $hasImage = !empty($person['image']);
  $hasSlug = !empty($person['slug']);
  $classes = 'nm-node-card';

  if (!empty($person['featured'])) $classes .= ' is-featured';
  if (!empty($person['female'])) $classes .= ' is-female';
@endphp

@if($hasSlug)
  <a href="{{ route('ktitors.show', $person['slug']) }}" class="{{ $classes }}">
@else
  <div class="{{ $classes }}">
@endif

    <div class="nm-node-card__media">
      @if($hasImage)
        <img src="{{ asset('images/ktitors/' . $person['image']) }}" alt="{{ $person['name'] }}">
      @else
        <div class="nm-node-card__placeholder">
          {{ mb_substr($person['name'], 0, 1) }}
        </div>
      @endif
    </div>

    <div class="nm-node-card__body">
      <h3>{{ $person['name'] }}</h3>

      @if(!empty($person['role']))
        <div class="nm-node-card__role">{{ $person['role'] }}</div>
      @endif

      @if(!empty($person['years']))
        <div class="nm-node-card__years">{{ $person['years'] }}</div>
      @endif

      @if($hasSlug)
        <span class="nm-node-card__badge">profil</span>
      @endif
    </div>

@if($hasSlug)
  </a>
@else
  </div>
@endif