@extends('layouts.site')

@section('title', ($ktitor->name ?? 'Ktitor') . ' — Pravoslavni Svetionik')
@section('nav_ktitors', 'active')
@section('content')
<section class="section ktitor-show-page">
  <div class="container ktitor-show-container">

    <a class="btn2 ktPro__back" href="{{ route('ktitors.index') }}">← Nazad na listu</a>

    {{-- ZAGLAVLJE --}}
    <div class="ktHeaderCard">
      <div class="ktHeaderCard__inner">
        <div class="ktHeaderCard__content">
          <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h1 class="ktHeaderCard__title" style="margin: 0;">{{ $ktitor->name ?? 'Ktitor' }}</h1>
            @if($ktitor->is_saint)
              <span class="kt-saint-header-pill">
                🕊️ Светитељ СПЦ @if(!empty($ktitor->feast_day)) • {{ explode(' (', $ktitor->feast_day)[0] }} @endif
              </span>
            @endif
          </div>
        </div>
        <div class="ktHeaderCard__actions">
          <a class="btn2 btn2--ghost" href="#biografija">Биографија</a>
        </div>
      </div>
    </div>

    @php
      use Illuminate\Support\Str;

      $galleryImages = $ktitor->images->sortBy('sort')->values();
      $hasGallery = $galleryImages->isNotEmpty();
      $fallbackImg = asset('images/sample/studenica.jpg');

      $firstImage = $galleryImages->first();
      $mainImageUrl = $firstImage ? $firstImage->image_src : $ktitor->image_src;

      $rawBio = trim((string)($ktitor->bio ?? ''));
      $lead = null;
      $sections = [];
      
      if ($rawBio !== '') {
          if (preg_match('/(?:Kratak opis|Uvod):\s*(.+?)(?:\n|$)/us', $rawBio, $m)) {
              $lead = trim($m[1]);
          } else {
              $paragraphs = preg_split("/\n\s*\n/u", $rawBio);
              $lead = trim($paragraphs[0]);
          }

          $chunks = preg_split("/\n\s*\n/u", $rawBio);
          foreach ($chunks as $ch) {
              $ch = trim($ch);
              if ($ch === '' || str_contains($ch, 'Kratak opis:')) continue;
              
              if (preg_match('/^(.{2,80}):\s*(.*)$/us', $ch, $m)) {
                  $sections[] = ['title' => trim(rtrim($m[1], ':')), 'body' => trim($m[2])];
              } else {
                  $sections[] = ['title' => 'Biografija', 'body' => $ch];
              }
          }
      }

      $years = ($ktitor->born_year || $ktitor->died_year)
        ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
        : null;

      $ktGalleryListJson = json_encode($galleryImages->map(function($img) {
          return [
              'id' => $img->id,
              'url' => $img->image_src,
              'caption' => $img->caption,
              'source' => $img->source ?? null,
          ];
      })->values(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    @endphp

    {{-- DVO-KOLONARNI LAYOUT --}}
    <div class="ktGrid" id="biografija">
      
      {{-- GLAVNI TEKST --}}
      <div class="ktMain text-book-layout">
        
        @if($lead)
          <div class="kt-hero-lead-block">
            <p class="kt-hero__lead">{{ $lead }}</p>
            <div class="kt-separator"><span class="kt-separator__ornament">❧</span></div>
          </div>
        @else
          <p class="kt-paragraph muted">Detaljna biografija za ovu istorijsku ličnost je trenutno u pripremi.</p>
        @endif

        @if(!empty($sections))
          @foreach($sections as $s)
            @php
              $paras = array_values(array_filter(array_map('trim', preg_split("/\n+/u", $s['body']) ?: [])));
            @endphp
            
            <article class="kt-book-section">
              <div class="kt-book-section__head">
                <h3>{{ $s['title'] }}</h3>
              </div>
              <div class="kt-book-section__body">
                @foreach($paras as $p)
                  @php
                    $cleanParagraph = preg_replace('/^[A-ZČĆŠĐŽa-zčćšđž ]+:\s*/u', '', $p);
                  @endphp
                  <p class="kt-paragraph">{{ $cleanParagraph }}</p>
                @endforeach
              </div>
              @if(!$loop->last)
                <div class="kt-separator"><span class="kt-separator__ornament">❧</span></div>
              @endif
            </article>
          @endforeach
        @endif

        {{-- POETSKI SPOMENIK: PESMA SIMONIDA OD MILANA RAKIĆA --}}
        @if(($ktitor->slug ?? '') === 'simonida')
          <div class="simonida-poem-card">
            <div class="simonida-poem-header">
              <span class="simonida-poem-badge">👑 Поетски споменик краљици</span>
              <h3 class="simonida-poem-title">„Симонида”</h3>
              <div class="simonida-poem-author">Милан Ракић (1876–1938)</div>
              <p class="simonida-poem-intro">
                Настала 1912. године у манастиру Грачаници, ова антологијска песма представља један од најлепших врхунаца српске родољубиве и мисаоне лирике, инспирисана монументалном фреском српске краљице.
              </p>
            </div>

            <div class="simonida-poem-divider"><span>❦</span></div>

            <div class="simonida-poem-body">
              <div class="simonida-stanza">
                <p>Ископаше ти очи, лепа слико!<br>
                Вечери једне, на каменој плочи,<br>
                Знајући да га тад не види нико,<br>
                Арбанас ти је ножем избо очи!</p>
              </div>

              <div class="simonida-stanza">
                <p>Али дирнути руком није смео<br>
                Ни отмено ти лице, нити уста,<br>
                Ни златну круну, ни краљевски вео,<br>
                Под којим лежи коса твоја густа.</p>
              </div>

              <div class="simonida-stanza">
                <p>И сад у цркви, на каменом стубу,<br>
                У искићеном мозаик-оделу,<br>
                Док мирно сносиш судбу твоју грубу,<br>
                Гледам те тужну, свечану, и белу;</p>
              </div>

              <div class="simonida-stanza">
                <p>И као звезде угашене, које<br>
                Човеку ипак шаљу светлост своју,<br>
                И човек види сјај, облик, и боју<br>
                Далеких звезда што већ не постоје,</p>
              </div>

              <div class="simonida-stanza">
                <p>Тако на мене са мрачнога зида,<br>
                На почађалој и старинској плочи,<br>
                Сијају сада, тужна Симонида,<br>
                Твоје већ давно ископане очи...</p>
              </div>
            </div>

            <div class="simonida-poem-footer">
              <div class="simonida-note-ornament">❧</div>
              <div class="simonida-note-content">
                <strong>Књижевно-историјски осврт:</strong> Милан Ракић је песму написао док је службовао као српски конзул у Приштини. Према народном веровању тога доба, сматрало се да су очи на фресци оштећене сечивом освајача. Детаљна рестаурација и чишћење фресака у Грачаници 1971. године, које су извели конзерватори Зденко и Бранислав Живковић, открила су да је оштећење настало вековним осипањем креча и злата, након чега су очи краљице делимично изрониле. Ипак, Ракићева поетска визија очију које сијају попут угашених далеких звезда остала је трајни симбол српске културне баштине и страдања.
              </div>
            </div>
          </div>
          <div class="kt-separator"><span class="kt-separator__ornament">❧</span></div>
        @endif

        {{-- MANASTIRI --}}
        @if($ktitor->manastiri && $ktitor->manastiri->count())
          <section class="kt-info-panel" style="margin-top: 30px; padding: 22px 24px; border-radius: 20px; background: rgba(255, 255, 255, 0.02); border: 1.5px solid rgba(197, 162, 74, 0.25);">
            <div class="kt-info-panel__title" style="color: var(--gold, #c5a24a); font-size: 1.15rem; margin-bottom: 14px; font-weight: 700;">
              🏛️ Повезане задужбине и манастири ({{ $ktitor->manastiri->count() }})
            </div>
            <div class="kt-tags-list" style="display: flex; flex-wrap: wrap; gap: 10px;">
              @foreach($ktitor->manastiri as $m)
                <a href="{{ route('monasteries.show', $m->slug) }}" class="kt-custom-tag">{{ $m->name ?? 'Manastir' }}</a>
              @endforeach
            </div>
          </section>
        @endif

        {{-- VIDEO DOKUMENTARAC / EMISIJA O KTITORU (HISTORYCAST / RTS) --}}
        @php
            $ktitorVideos = \App\Support\EducationalMedia::forKtitor($ktitor->slug);
            $videoCount = !empty($ktitorVideos) ? count($ktitorVideos) : 0;
        @endphp
        @if($videoCount > 0)
          <section class="kt-videos-section" style="margin-top: 35px; padding: 22px 24px; border-radius: 22px; background: linear-gradient(180deg, rgba(28,18,17,.96), rgba(14,9,9,.96)); border: 1.5px solid rgba(197, 162, 74, 0.35); box-shadow: 0 16px 40px rgba(0,0,0,.35); {{ $videoCount === 1 ? 'max-width: 520px;' : 'width: 100%;' }}">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid rgba(197, 162, 74, 0.22); padding-bottom: 10px;">
              <span style="font-size: 1.25rem;">🎬</span>
              <h3 style="margin: 0; font-size: 1.18rem; color: var(--gold, #c5a24a); font-weight: 800;">
                {{ $videoCount === 1 ? 'Историјска ТВ емисија и документарац' : 'Историјске ТВ емисије и документарци' }}
              </h3>
            </div>

            <div class="kt-videos-grid" style="display: grid; grid-template-columns: {{ $videoCount === 1 ? '1fr' : 'repeat(auto-fit, minmax(280px, 1fr))' }}; gap: 18px;">
              @foreach($ktitorVideos as $v)
                <div class="kt-video-card" style="display: flex; flex-direction: column; background: rgba(18, 11, 10, 0.85); border: 1px solid rgba(197, 162, 74, 0.25); border-radius: 16px; padding: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.45); transition: border-color 0.2s ease; width: 100%; {{ $videoCount === 1 ? 'max-width: 480px;' : '' }}">
                  <div style="position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 10px; overflow: hidden; background: #000; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 6px 18px rgba(0,0,0,0.6);">
                    <iframe
                      src="{{ $v['embed_url'] }}"
                      title="{{ $v['title'] }}"
                      style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen
                      loading="lazy"
                    ></iframe>
                  </div>
                  <div style="margin-top: 10px; font-size: 0.9rem; font-weight: 700; color: #fff; line-height: 1.4;">
                    {{ $v['title'] }}
                  </div>
                  @if(!empty($v['author']))
                    <div style="font-size: 0.78rem; color: #e2c26a; margin-top: 4px; font-weight: 600;">
                      🏛️ {{ $v['author'] }}
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </section>
        @endif
      </div>

      {{-- DESNI PANEL --}}
      <aside class="ktSide">
        
        {{-- Centrirana glavna fotografija sa mini-galerijom (isti princip kao kod manastira) --}}
        <div class="monSideBannerCard ktSideBannerCard">
          <div class="monSideBannerPhoto ktSideBannerPhoto" onclick="openKtLightbox(currentKtSideIndex)" title="Kliknite za pregled fotografije u punoj rezoluciji">
            <img
              id="ktMainPortraitImg"
              src="{{ $mainImageUrl }}"
              alt="Fotografija: {{ $ktitor->name ?? 'Ktitor' }}"
              loading="lazy"
              onerror="this.onerror=null;this.src='{{ $fallbackImg }}';"
            />
            <div class="monSideBannerPhoto__badge ktSideBannerPhoto__badge">
              @if($hasGallery && $galleryImages->count() > 1)
                📸 Galerija ({{ $galleryImages->count() }})
              @else
                🔍 Uvećaj fotografiju
              @endif
            </div>
          </div>

          {{-- Mini traka sa sličicama ukoliko ima više fotografija --}}
          @if($hasGallery && $galleryImages->count() > 1)
            <div class="monSideGalleryStrip ktSideGalleryStrip">
              @foreach($galleryImages as $thumbIdx => $thumb)
                <button 
                  type="button" 
                  class="monSideThumbItem ktSideThumbItem {{ $thumbIdx === 0 ? 'active' : '' }}" 
                  onclick="selectKtSideThumb({{ $thumbIdx }}, '{{ $thumb->image_src }}')"
                  title="{{ strip_tags($thumb->caption ?? 'Slika ' . ($thumbIdx + 1)) }}"
                  aria-label="Prikaži sliku {{ $thumbIdx + 1 }}"
                >
                  <img 
                    src="{{ $thumb->image_src }}" 
                    alt="{{ strip_tags($thumb->caption ?? 'Pregled') }}" 
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ $fallbackImg }}';"
                  >
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <div class="card ktSide__card">
          <h3 class="ktSide__title">Informacije</h3>
          <div class="ktKV">
            @if($years)
              <div class="ktKV__row">
                <div class="ktKV__k">Vreme života</div>
                <div class="ktKV__v nm-gold-highlight">{{ $years }}</div>
              </div>
            @endif
            @if(!empty($ktitor->title))
              <div class="ktKV__row">
                <div class="ktKV__k">Titula / Status</div>
                <div class="ktKV__v nm-gold-highlight">{{ $ktitor->title }}</div>
              </div>
            @endif
            @if(!empty($ktitor->dynasty))
              <div class="ktKV__row">
                <div class="ktKV__k">Dinastija</div>
                <div class="ktKV__v">{{ $ktitor->dynasty }}</div>
              </div>
            @endif
            @if(isset($ktitor->is_saint))
              <div class="ktKV__row">
                <div class="ktKV__k">Канонизација</div>
                <div class="ktKV__v nm-gold-highlight">
                  {{ $ktitor->is_saint ? 'Да (Светитељ СПЦ 🕊️)' : 'Не' }}
                </div>
              </div>
            @endif
            @if(!empty($ktitor->saint_name))
              <div class="ktKV__row">
                <div class="ktKV__k">Светитељско име</div>
                <div class="ktKV__v" style="color: #e2c26a; font-weight: 700;">
                  {{ $ktitor->saint_name }}
                </div>
              </div>
            @endif
            @if(!empty($ktitor->feast_day))
              <div class="ktKV__row ktKV__row--feast" style="background: rgba(197, 162, 74, 0.08); border-radius: 12px; padding: 10px 12px; margin: 4px 0; border: 1px solid rgba(197, 162, 74, 0.28); flex-direction: column; align-items: flex-start; gap: 4px;">
                <div class="ktKV__k" style="color: #e2c26a; font-weight: 800; display: flex; align-items: center; gap: 6px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
                  <span>🗓️</span> Дан прослављања у СПЦ:
                </div>
                <div class="ktKV__v" style="color: #fff; font-weight: 700; text-align: left; line-height: 1.45; font-size: 0.88rem;">
                  {{ $ktitor->feast_day }}
                </div>
              </div>
            @endif
            @if(!empty($ktitor->burial_place))
              <div class="ktKV__row">
                <div class="ktKV__k">Mesto sahrane</div>
                <div class="ktKV__v">{{ $ktitor->burial_place }}</div>
              </div>
            @endif
            @if($ktitor->manastiri->isNotEmpty())
              <div class="ktKV__row" style="flex-direction: column; align-items: flex-start; gap: 8px; border-bottom: none;">
                <div class="ktKV__k" style="color: var(--gold, #c5a24a); font-weight: 700;">Zadužbine:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                  @foreach($ktitor->manastiri as $manastir)
                    <a href="{{ route('monasteries.show', $manastir->slug) }}" class="kt-custom-tag" style="font-size: 0.78rem; padding: 4px 10px;">
                      {{ $manastir->name }}
                    </a>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="kt-kustos-wrapper" style="margin-top: 25px; padding: 20px; background: #1a1512; border: 1px solid #332720; border-radius: 16px;">
              <h4 style="color: #c5a059; margin-bottom: 15px; font-family: inherit;">✨🖋️ Дигитални Летописац 🕯️📜</h4>
              @include('kustos.chat', [
                  'entitet' => $ktitor, 
                  'tip' => 'ktitor'
              ])
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

{{-- MODAL LIGHTBOX ZA PREGLED I ZUMIRANJE GALERIJE KTITORA --}}
<div id="ktLightbox" class="mon-lightbox" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="mon-lightbox__backdrop" onclick="closeKtLightbox()"></div>
    
    <div class="mon-lightbox__dialog">
        <div class="mon-lightbox__header">
            <div class="mon-lightbox__tools">
                <button type="button" class="mon-lightbox__tool-btn" onclick="zoomKtLightbox(0.3)" title="Uvećaj sliku (+)" aria-label="Uvećaj">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="11" y1="8" x2="11" y2="14"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <span>Uvećaj</span>
                </button>
                <button type="button" class="mon-lightbox__tool-btn" onclick="zoomKtLightbox(-0.3)" title="Umanji sliku (-)" aria-label="Umanji">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <span>Umanji</span>
                </button>
                <button type="button" class="mon-lightbox__tool-btn" onclick="resetKtLightboxZoom()" title="Resetuj zum (100%)" aria-label="Resetuj">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    <span id="ktZoomLevelText">100%</span>
                </button>
            </div>
            <button type="button" class="mon-lightbox__close" onclick="closeKtLightbox()" aria-label="Zatvori galeriju">&times;</button>
        </div>
        
        <button type="button" class="mon-lightbox__nav mon-lightbox__nav--prev" onclick="prevKtLightboxImage()" aria-label="Prethodna fotografija">&#10094;</button>
        <button type="button" class="mon-lightbox__nav mon-lightbox__nav--next" onclick="nextKtLightboxImage()" aria-label="Sledeća fotografija">&#10095;</button>

        <div class="mon-lightbox__stage" id="ktLightboxStage">
            <img id="ktLightboxImg" src="" alt="" draggable="false" onerror="this.onerror=null;this.src='{{ $fallbackImg }}'">
            <div class="mon-lightbox__hint" id="ktLightboxHint">🔍 Dupli klik za uvećanje / skrol mišem za zum</div>
        </div>

        <div class="mon-lightbox__footer">
            <div class="mon-lightbox__footer-header">
                <span class="mon-lightbox__footer-badge">
                    <span>🖼️</span> Опис фотографије
                </span>
                <span id="ktLightboxCounter" class="mon-lightbox__counter"></span>
            </div>
            <div id="ktLightboxCaption" class="mon-lightbox__caption"></div>
        </div>
    </div>
</div>

<style>
.ktitor-show-page { padding-top: 20px; padding-bottom: 50px; }
.ktitor-show-container { max-width: 1500px !important; }

.kt-saint-header-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(197, 162, 74, 0.22), rgba(197, 162, 74, 0.08));
    border: 1.5px solid rgba(197, 162, 74, 0.45);
    color: #fce7aa;
    font-size: 0.88rem;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(197, 162, 74, 0.2);
}

.btn2.ktPro__back {
    display: inline-flex;
    align-items: center;
    padding: 10px 18px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 700;
    background: rgba(255,255,255,.05);
    color: rgba(255,255,255,.92);
    border: 1px solid rgba(255,255,255,.10);
    margin-bottom: 20px;
}

.ktHeaderCard {
    padding: 24px;
    border-radius: 28px;
    border: 1px solid rgba(255,255,255,.08);
    background: linear-gradient(135deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow: 0 20px 48px rgba(0,0,0,.30);
    margin-bottom: 24px;
}
.ktHeaderCard__inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.ktHeaderCard__title { margin: 0; font-size: 2.4rem; font-weight: 800; color: #c5a24a; text-shadow: 0 0 14px rgba(197,162,74,.15); }
.ktHeaderCard__actions { display: flex; gap: 12px; }

.ktGrid {
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(360px, 440px);
    gap: 36px;
    align-items: start;
    margin-top: 20px;
}

.ktSide {
    width: 100%;
    max-width: 440px;
    margin: 0 auto;
}

/* CENTRIRANJE I ESTETIKA GLAVNE SLIKE I THUMBNAIL STRIP */
.monSideBannerCard, .ktSideBannerCard {
    max-width: 440px;
    margin: 0 auto 16px auto;
}

.monSideBannerPhoto, .ktSideBannerPhoto {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 11;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(197, 162, 74, 0.3);
    box-shadow: 0 14px 38px rgba(0, 0, 0, 0.5);
    cursor: pointer;
    background: #140d0d;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}
.monSideBannerPhoto img, .ktSideBannerPhoto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform 0.5s ease;
}
.monSideBannerPhoto:hover, .ktSideBannerPhoto:hover {
    border-color: rgba(197, 162, 74, 0.6);
    box-shadow: 0 18px 45px rgba(197, 162, 74, 0.18);
}
.monSideBannerPhoto:hover img, .ktSideBannerPhoto:hover img {
    transform: scale(1.05);
}
.monSideBannerPhoto__badge, .ktSideBannerPhoto__badge {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(15, 10, 10, 0.92);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(197, 162, 74, 0.45);
    color: #e2c26a;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.6);
    pointer-events: none;
}

.monSideGalleryStrip, .ktSideGalleryStrip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 10px;
}
.monSideThumbItem, .ktSideThumbItem {
    position: relative;
    aspect-ratio: 4 / 3;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    padding: 0;
    background: #140d0d;
    cursor: pointer;
    transition: all 0.2s ease;
}
.monSideThumbItem img, .ktSideThumbItem img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.monSideThumbItem:hover, .ktSideThumbItem:hover {
    transform: translateY(-2px);
    border-color: rgba(197, 162, 74, 0.6);
}
.monSideThumbItem.active, .ktSideThumbItem.active {
    border-color: #c5a24a;
    box-shadow: 0 0 10px rgba(197, 162, 74, 0.45);
}

.kt-kustos-wrapper {
    margin-top: 24px;
    padding: 20px;
    background: linear-gradient(180deg, #201714, #140d0c);
    border: 1.5px solid rgba(197, 162, 74, 0.35);
    border-radius: 22px;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
}

.text-book-layout {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.kt-hero__lead {
    font-size: 17.5px;
    line-height: 1.9;
    color: rgba(255, 255, 255, 0.95);
    font-style: italic;
    text-align: justify;
    text-justify: inter-word;
    margin-bottom: 20px;
}

.kt-book-section {
    background: transparent !important;
    border: none !important;
    margin-bottom: 10px;
}

.kt-book-section__head h3 {
    font-size: 24px;
    color: #c5a24a;
    font-weight: 800;
    margin: 0 0 16px 0;
    letter-spacing: -0.01em;
}

.kt-paragraph {
    font-size: 16px;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.86);
    text-align: justify;
    text-justify: inter-word;
    margin-bottom: 18px;
}

.kt-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 28px 0;
}
.kt-separator::before,
.kt-separator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.35), transparent);
}
.kt-separator__ornament {
    padding: 0 16px;
    color: #c5a24a;
    font-size: 1.1rem;
    opacity: 0.8;
}

.kt-custom-tag {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s ease;
}
.kt-custom-tag:hover {
    border-color: #c5a24a;
    background: rgba(197, 162, 74, 0.15);
    transform: translateY(-1px);
}

/* SIMONIDA POEM CARD STYLING */
.simonida-poem-card {
    margin: 35px 0 25px 0;
    padding: 36px 38px 32px;
    border-radius: 26px;
    background: radial-gradient(circle at top, rgba(197, 162, 74, 0.12), transparent 60%), linear-gradient(180deg, rgba(28, 18, 16, 0.98), rgba(16, 10, 10, 0.98));
    border: 1.5px solid rgba(197, 162, 74, 0.45);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65), 0 0 25px rgba(197, 162, 74, 0.1);
    position: relative;
    overflow: hidden;
}
.simonida-poem-header {
    text-align: center;
    margin-bottom: 24px;
}
.simonida-poem-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 999px;
    background: rgba(197, 162, 74, 0.12);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 0.84rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}
.simonida-poem-title {
    font-size: clamp(2rem, 3.5vw, 2.8rem);
    color: var(--gold, #c5a24a);
    margin: 0 0 6px 0;
    font-weight: 800;
    font-family: Georgia, "Times New Roman", serif;
    letter-spacing: 1px;
    text-shadow: 0 0 16px rgba(197, 162, 74, 0.25);
}
.simonida-poem-author {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 600;
    margin-bottom: 14px;
    letter-spacing: 0.5px;
}
.simonida-poem-intro {
    width: 100%;
    margin: 0 auto;
    font-size: 0.98rem;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.88);
    text-align: justify;
    text-justify: inter-word;
}
.simonida-poem-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 22px 0 28px 0;
}
.simonida-poem-divider::before,
.simonida-poem-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.5), transparent);
}
.simonida-poem-divider span {
    padding: 0 16px;
    color: #c5a24a;
    font-size: 1.2rem;
}
.simonida-poem-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 26px;
    margin-bottom: 30px;
    width: 100%;
    text-align: center;
}
.simonida-stanza {
    text-align: center;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.simonida-stanza p {
    margin: 0 auto;
    text-align: center;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 1.18rem;
    line-height: 2.1;
    color: rgba(255, 255, 255, 0.98);
    font-style: italic;
    letter-spacing: 0.4px;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    display: inline-block;
}
.simonida-poem-footer {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: rgba(14, 9, 8, 0.85);
    border: 1px solid rgba(197, 162, 74, 0.3);
    border-radius: 18px;
    padding: 18px 22px;
    margin-top: 10px;
    width: 100%;
}
.simonida-note-ornament {
    color: #c5a24a;
    font-size: 1.4rem;
    line-height: 1;
    margin-top: 2px;
}
.simonida-note-content {
    font-size: 0.92rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.88);
    text-align: justify;
    text-justify: inter-word;
    flex: 1;
}

@media (max-width: 640px) {
    .simonida-poem-card { padding: 24px 18px 20px; }
    .simonida-stanza p { font-size: 1rem; line-height: 1.8; }
    .simonida-poem-footer { flex-direction: column; gap: 8px; }
}

.ktSide__card {
    padding: 24px;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    background: linear-gradient(180deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow: 0 14px 36px rgba(0,0,0,.3);
}
.ktSide__title { font-size: 20px; color: #fff; font-weight: 800; margin-bottom: 16px; }

.ktKV { display: flex; flex-direction: column; gap: 12px; }
.ktKV__row { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,.04); }
.ktKV__row:last-child { border-bottom: 0; padding-bottom: 0; }
.ktKV__k { font-size: 13.5px; color: rgba(255,255,255,.55); font-weight: 500; }
.ktKV__v { font-size: 14px; color: rgba(255,255,255,.9); text-align: right; font-weight: 600; }
.ktKV__v.nm-gold-highlight { color: #e2c26a !important; font-weight: 700; }

@media (max-width: 1100px) {
    .ktGrid { grid-template-columns: 1fr; gap: 30px; }
    .ktSide { max-width: 460px; margin: 0 auto; width: 100%; }
    .ktHeaderCard__title { font-size: 2rem; }
}

/* LIGHTBOX MODAL SA ZUMOM I LUPOM - POZICIONIRANO ISPOD NAVBARA */
.mon-lightbox {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 2000000 !important; /* Znatno iznad fiksnog navbara */
    display: flex !important;
    align-items: flex-start !important; /* Poravnanje na vrh */
    justify-content: center !important;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    padding: 100px 20px 40px !important; /* 100px gornji padding da prozor bude ispod navbara */
    box-sizing: border-box !important;
    overflow-y: auto !important; /* Skrolovanje ako dijalog premaši ekran */
}
.mon-lightbox.active {
    opacity: 1 !important;
    pointer-events: auto !important;
}
.mon-lightbox__backdrop {
    position: absolute !important;
    inset: 0 !important;
    background: rgba(4, 3, 3, 0.96) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
}
.mon-lightbox__dialog {
    position: relative !important;
    z-index: 2 !important;
    width: min(1180px, 92vw) !important;
    max-height: calc(100vh - 140px) !important; /* Prilagođena visina da stane u preostali prostor */
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    background: linear-gradient(180deg, rgba(26, 17, 16, 0.98), rgba(16, 10, 10, 0.98)) !important;
    border: 1.5px solid rgba(197, 162, 74, 0.45) !important;
    border-radius: 24px !important;
    box-shadow: 0 30px 80px rgba(0,0,0,0.9), 0 0 50px rgba(197, 162, 74, 0.2) !important;
    padding: 18px 24px 20px 24px !important;
    box-sizing: border-box !important;
    margin: 0 auto !important; /* Nema auto gornje/donje margine */
}

.mon-lightbox__header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    margin-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.mon-lightbox__tools {
    display: flex;
    align-items: center;
    gap: 8px;
}
.mon-lightbox__tool-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(35, 24, 22, 0.85);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mon-lightbox__tool-btn:hover {
    background: rgba(197, 162, 74, 0.2);
    border-color: #c5a24a;
    color: #fff;
    transform: translateY(-1px);
}
.mon-lightbox__tool-btn:active {
    transform: scale(0.96);
}

.mon-lightbox__close {
    background: rgba(0,0,0,0.5);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    font-size: 26px;
    line-height: 1;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.2s;
}
.mon-lightbox__close:hover {
    color: var(--gold, #c5a24a);
    border-color: var(--gold, #c5a24a);
    transform: scale(1.1);
}

.mon-lightbox__stage {
    position: relative;
    width: 100%;
    height: 58vh;
    max-height: 580px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 14px;
    background: #080404;
    cursor: zoom-in;
    user-select: none;
    touch-action: none;
}
.mon-lightbox__stage img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.6);
    transform-origin: center center;
    transition: transform 0.15s ease-out;
    will-change: transform;
}
.mon-lightbox__hint {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(10, 6, 6, 0.75);
    border: 1px solid rgba(197, 162, 74, 0.25);
    color: rgba(255, 255, 255, 0.75);
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    pointer-events: none;
    opacity: 0.85;
    transition: opacity 0.3s;
}

.mon-lightbox__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(18, 12, 12, 0.8);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 24px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 5;
}
.mon-lightbox__nav:hover {
    background: var(--gold, #c5a24a);
    color: #120b0b;
    transform: translateY(-50%) scale(1.12);
}
.mon-lightbox__nav--prev { left: 24px; }
.mon-lightbox__nav--next { right: 24px; }

.mon-lightbox__footer {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 14px;
    padding: 12px 18px;
    background: rgba(14, 9, 8, 0.88);
    border: 1px solid rgba(197, 162, 74, 0.3);
    border-radius: 16px;
    box-sizing: border-box;
}
.mon-lightbox__footer-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid rgba(197, 162, 74, 0.18);
}
.mon-lightbox__footer-badge {
    color: var(--gold, #c5a24a);
    font-size: 0.82rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.mon-lightbox__caption {
    font-size: 1.05rem;
    font-weight: 500;
    color: #f5f1ea;
    text-align: left;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}
.mon-lightbox__caption-desc {
    color: #ffffff;
    font-size: 1.04rem;
    font-weight: 600;
    line-height: 1.55;
    text-align: justify;
    text-justify: inter-word;
    margin: 0 0 6px 0;
    width: 100%;
}
.mon-lightbox__caption-source {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #facc15 !important; /* Žuta slova */
    font-style: italic !important; /* Kurziv */
    font-size: 0.88rem !important;
    font-weight: 600;
    line-height: 1.35;
    padding: 3px 10px;
    background: rgba(250, 204, 21, 0.12);
    border: 1px solid rgba(250, 204, 21, 0.30);
    border-radius: 8px;
    margin-top: 4px;
    width: fit-content;
}
.mon-lightbox__counter {
    font-size: 0.86rem;
    color: #fce7aa;
    font-weight: 800;
    padding: 3px 12px;
    background: rgba(197, 162, 74, 0.18);
    border: 1px solid rgba(197, 162, 74, 0.35);
    border-radius: 999px;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .mon-lightbox__dialog {
        padding: 14px;
        width: 96vw;
    }
    .mon-lightbox__nav {
        width: 38px;
        height: 38px;
        font-size: 18px;
    }
    .mon-lightbox__nav--prev { left: 8px; }
    .mon-lightbox__nav--next { right: 8px; }
}
</style>

<script>
    const ktGalleryList = {!! $ktGalleryListJson !!};

    let curKtLightboxIdx = 0;
    let currentKtSideIndex = 0;
    let ktZoom = 1.0;
    let ktPanX = 0;
    let ktPanY = 0;
    let ktIsDragging = false;
    let ktStartX = 0;
    let ktStartY = 0;

    function formatKtLightboxCaption(caption) {
        if (!caption || typeof caption !== 'string') return '';

        let desc = caption;
        let source = '';

        // Provera HTML formata <small><em>...</em></small>
        const htmlMatch = caption.match(/^(.*?)(?:<br\s*\/?>)?\s*<small[^>]*>(?:<em>)?(?:\*|\()?([^*()<>\n]+(?:\.rs|[a-zA-Z0-9\s\.\-_]+))(?:\*|\))?(?:<\/em>)?<\/small>$/i);
        if (htmlMatch) {
            desc = htmlMatch[1].trim();
            source = htmlMatch[2].trim();
        } else {
            // Provera plaintext / markdown formata
            const textMatch = caption.match(/^(.*?)(?:\s*[-–—]|\s*<br\s*\/?>|\s*\n)?\s*(?:\*|\()?\s*(Izvor:\s*[^)*\n]+)(?:\*|\))?$/i);
            if (textMatch) {
                desc = textMatch[1].trim();
                source = textMatch[2].trim();
            }
        }

        source = source.replace(/^[(*\s]+/, '').replace(/[)*\s]+$/, '').trim();
        if (source && !source.toLowerCase().startsWith('izvor:') && !source.toLowerCase().startsWith('извор:')) {
            source = 'Izvor: ' + source;
        }

        const mode = (typeof window.getSiteScript === 'function') 
            ? window.getSiteScript() 
            : (localStorage.getItem('site_script') || 'lat');

        if (typeof window.convertSiteText === 'function') {
            desc = window.convertSiteText(desc, mode);
            source = window.convertSiteText(source, mode);
        } else if (mode === 'cyr' && typeof window.latToCyr === 'function') {
            desc = window.latToCyr(desc);
            source = window.latToCyr(source);
        }

        if (desc.length > 0 && source.length > 0) {
            return `<div class="mon-lightbox__caption-desc">${desc}</div><div class="mon-lightbox__caption-source" style="color: #eab308; font-style: italic; margin-top: 4px;">*${source}*</div>`;
        } else if (source.length > 0) {
            return `<div class="mon-lightbox__caption-source" style="color: #eab308; font-style: italic;">*${source}*</div>`;
        }
        return `<div class="mon-lightbox__caption-desc">${desc}</div>`;
    }

    function openKtLightbox(index) {
        if (!ktGalleryList || ktGalleryList.length === 0) return;
        curKtLightboxIdx = (typeof index === 'number' && index >= 0 && index < ktGalleryList.length) ? index : 0;
        resetKtLightboxZoom();
        updateKtLightboxUI();
        const lb = document.getElementById('ktLightbox');
        if (lb) {
            lb.classList.add('active');
            lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            if (typeof window.applySiteScriptToNode === 'function') {
                window.applySiteScriptToNode(lb);
            }
        }
    }

    function closeKtLightbox() {
        const lb = document.getElementById('ktLightbox');
        if (lb) {
            lb.classList.remove('active');
            lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            resetKtLightboxZoom();
        }
    }

    function updateKtZoomTransform(smooth = true) {
        const imgEl = document.getElementById('ktLightboxImg');
        const stage = document.getElementById('ktLightboxStage');
        const zoomText = document.getElementById('ktZoomLevelText');
        if (!imgEl) return;

        imgEl.style.transition = smooth ? 'transform 0.15s ease-out' : 'none';
        imgEl.style.transform = `translate(${ktPanX}px, ${ktPanY}px) scale(${ktZoom})`;
        if (zoomText) zoomText.textContent = `${Math.round(ktZoom * 100)}%`;

        if (stage) {
            if (ktZoom > 1.05) {
                stage.style.cursor = ktIsDragging ? 'grabbing' : 'grab';
            } else {
                stage.style.cursor = 'zoom-in';
            }
        }
    }

    function zoomKtLightbox(delta) {
        let newZoom = Math.min(Math.max(ktZoom + delta, 0.75), 4.0);
        newZoom = Math.round(newZoom * 100) / 100;
        if (newZoom === ktZoom) return;
        ktZoom = newZoom;
        if (ktZoom <= 1.05) {
            ktPanX = 0;
            ktPanY = 0;
        }
        updateKtZoomTransform(true);
    }

    function resetKtLightboxZoom() {
        ktZoom = 1.0;
        ktPanX = 0;
        ktPanY = 0;
        updateKtZoomTransform(true);
    }

    function toggleKtLightboxZoom() {
        if (ktZoom > 1.1) {
            resetKtLightboxZoom();
        } else {
            ktZoom = 2.2;
            ktPanX = 0;
            ktPanY = 0;
            updateKtZoomTransform(true);
        }
    }

    function updateKtLightboxUI() {
        const item = ktGalleryList[curKtLightboxIdx];
        if (!item) return;
        
        const imgEl = document.getElementById('ktLightboxImg');
        const capEl = document.getElementById('ktLightboxCaption');
        const cntEl = document.getElementById('ktLightboxCounter');

        const mode = (typeof window.getSiteScript === 'function') 
            ? window.getSiteScript() 
            : (localStorage.getItem('site_script') || 'lat');

        if (imgEl) {
            imgEl.src = item.url;
            imgEl.alt = (typeof window.convertSiteText === 'function') 
                ? window.convertSiteText(item.caption || '', mode) 
                : (item.caption || '');
        }
        if (capEl) {
            capEl.innerHTML = formatKtLightboxCaption(item.caption || '');
        }
        if (cntEl) {
            cntEl.textContent = `${curKtLightboxIdx + 1} / ${ktGalleryList.length}`;
        }

        const lb = document.getElementById('ktLightbox');
        if (lb && typeof window.applySiteScriptToNode === 'function') {
            window.applySiteScriptToNode(lb, mode);
        }

        const prevBtn = document.querySelector('#ktLightbox .mon-lightbox__nav--prev');
        const nextBtn = document.querySelector('#ktLightbox .mon-lightbox__nav--next');
        if (ktGalleryList.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }
    }

    function nextKtLightboxImage() {
        if (!ktGalleryList || ktGalleryList.length <= 1) return;
        curKtLightboxIdx = (curKtLightboxIdx + 1) % ktGalleryList.length;
        resetKtLightboxZoom();
        updateKtLightboxUI();
    }

    function prevKtLightboxImage() {
        if (!ktGalleryList || ktGalleryList.length <= 1) return;
        curKtLightboxIdx = (curKtLightboxIdx - 1 + ktGalleryList.length) % ktGalleryList.length;
        resetKtLightboxZoom();
        updateKtLightboxUI();
    }

    function selectKtSideThumb(idx, url) {
        currentKtSideIndex = idx;
        const mainImg = document.getElementById('ktMainPortraitImg');
        if (mainImg) mainImg.src = url;

        document.querySelectorAll('.ktSideThumbItem').forEach((btn, i) => {
            if (i === idx) btn.classList.add('active');
            else btn.classList.remove('active');
        });
    }

    window.addEventListener('sitescriptchange', function () {
        updateKtLightboxUI();
        const sideBanner = document.querySelector('.ktSideBannerCard');
        if (sideBanner && typeof window.applySiteScriptToNode === 'function') {
            window.applySiteScriptToNode(sideBanner);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const stage = document.getElementById('ktLightboxStage');
        if (!stage) return;

        stage.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.25 : -0.25;
            zoomKtLightbox(delta);
        }, { passive: false });

        stage.addEventListener('dblclick', toggleKtLightboxZoom);

        stage.addEventListener('mousedown', (e) => {
            if (ktZoom <= 1.05) return;
            ktIsDragging = true;
            ktStartX = e.clientX - ktPanX;
            ktStartY = e.clientY - ktPanY;
            stage.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!ktIsDragging) return;
            ktPanX = e.clientX - ktStartX;
            ktPanY = e.clientY - ktStartY;
            updateKtZoomTransform(false);
        });

        window.addEventListener('mouseup', () => {
            if (ktIsDragging) {
                ktIsDragging = false;
                updateKtZoomTransform(true);
            }
        });

        // Touch podrška za mobilne uređaje
        stage.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1 && ktZoom > 1.05) {
                ktIsDragging = true;
                ktStartX = e.touches[0].clientX - ktPanX;
                ktStartY = e.touches[0].clientY - ktPanY;
            }
        }, { passive: true });

        stage.addEventListener('touchmove', (e) => {
            if (ktIsDragging && e.touches.length === 1) {
                ktPanX = e.touches[0].clientX - ktStartX;
                ktPanY = e.touches[0].clientY - ktStartY;
                updateKtZoomTransform(false);
            }
        }, { passive: true });

        stage.addEventListener('touchend', () => {
            if (ktIsDragging) {
                ktIsDragging = false;
                updateKtZoomTransform(true);
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        const lb = document.getElementById('ktLightbox');
        if (!lb || !lb.classList.contains('active')) return;

        if (e.key === 'Escape') closeKtLightbox();
        else if (e.key === 'ArrowRight') nextKtLightboxImage();
        else if (e.key === 'ArrowLeft') prevKtLightboxImage();
        else if (e.key === '+' || e.key === '=') zoomKtLightbox(0.25);
        else if (e.key === '-' || e.key === '_') zoomKtLightbox(-0.25);
        else if (e.key === '0') resetKtLightboxZoom();
    });
</script>
@endsection