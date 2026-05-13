@extends('layouts.game')

@section('title', 'Put vladara')

@section('content')
<div class="pv-game" id="pvGame">
    <div class="pv-bg"></div>
    <div class="pv-overlay"></div>

    <button type="button" class="pv-float-help" id="floatingHelpBtn" aria-label="Pomo&#263;" title="Pomo&#263;">?</button>

    <div class="pv-toast-stack" id="toastStack" aria-live="polite"></div>

    {{-- START SCREEN --}}
    <section class="pv-screen pv-start-screen" id="startScreen">
        <div class="pv-start-card">
            <div class="pv-start-badge">Istorijska strategijska avantura</div>
            <h1 class="pv-title">Put vladara</h1>

            <p class="pv-subtitle">
                Preuzima&#353; ulogu mladog vladara. Kroz odluke, krize i savete dvora gradi&#353;
                snagu dr&#382;ave, poverenje naroda, mudrost, veru i nasle&#273;e koje ostaje posle tebe.
            </p>

            <div class="pv-start-goal">
                <h3>Glavni cilj</h3>
                <p>
                    Mo&#382;e&#353; da igraš <strong>Put vladara</strong> kroz 10 nivoa i 70 odluka, ili da odmah pokrene&#353; <strong>Put kroz manastire Srbije</strong> sa edukativnim mini-igrama.
                    Svaka odluka menja tvoje vrednosti: <strong>snagu</strong>, <strong>mudrost</strong>,
                    <strong>veru</strong>, <strong>ugled</strong>, <strong>zlato</strong> i <strong>stabilnost</strong>.
                </p>
            </div>

            <div class="pv-start-goal">
                <h3>Kako se pobe&#273;uje</h3>
                <p>
                    Najbolji zavr&#353;etak dobija&#353; ako do kraja o&#269;uva&#353; dr&#382;avu i razvije&#353; ugled,
                    veru i mudrost. Ako ugled ili stabilnost padnu na nulu, vladavina se zavr&#353;ava porazom.
                </p>
            </div>

            <div class="pv-mode-select" aria-label="Izbor moda igre">
                <button type="button" class="pv-mode-card pv-mode-primary" id="startGameBtn">
                    <span>Decision game</span>
                    <strong>Put vladara</strong>
                    <small>Vodi državu kroz 10 nivoa, odluke i posledice.</small>
                </button>
                <button type="button" class="pv-mode-card" id="startMiniGamesBtn">
                    <span>Mini igre</span>
                    <strong>Put kroz manastire Srbije</strong>
                    <small>Odmah otvori mapu, zadužbine, povelje, freske i zadatke.</small>
                </button>
            </div>

            <div class="pv-menu-buttons pv-secondary-menu">
                <button type="button" class="pv-btn pv-btn-secondary" id="toggleGuideBtn">Pravila</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="openSettingsBtn">Pode&#353;avanja</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="fullscreenBtn">Fullscreen</button>
            </div>

            <div class="pv-start-settings hidden" id="startSettingsBox">
                <div class="pv-start-settings-title">Pode&#353;avanja</div>

                <label class="pv-setting-row">
                    <span>Animacije</span>
                    <input type="checkbox" id="animToggle" checked>
                </label>

                <label class="pv-setting-row">
                    <span>Prikaz efekata odluke</span>
                    <input type="checkbox" id="effectsToggle" checked>
                </label>

                <label class="pv-setting-row">
                    <span>Upozorenja za kriti&#269;ne vrednosti</span>
                    <input type="checkbox" id="warningToggle" checked>
                </label>
            </div>

            <div class="pv-guide" id="guideBox">
                <div class="pv-guide-grid">
                    <div><strong>Snaga</strong><span>&#269;uva vojsku, granice i odlu&#269;nost u krizama.</span></div>
                    <div><strong>Mudrost</strong><span>otklju&#269;ava promi&#353;ljene odluke i bolje zavr&#353;etke.</span></div>
                    <div><strong>Vera</strong><span>ja&#269;a duhovni autoritet, Crkvu i zadu&#382;bine.</span></div>
                    <div><strong>Ugled</strong><span>pokazuje koliko ti narod, dvor i vlastela veruju.</span></div>
                    <div><strong>Zlato</strong><span>slu&#382;i za pomo&#263;, vojsku, gradnju i re&#353;avanje kriza.</span></div>
                    <div><strong>Stabilnost</strong><span>ako padne prenisko, dr&#382;ava klizi u haos.</span></div>
                    <div><strong>Nivoi</strong><span>igra ima 10 nivoa; svaki slede&#263;i nivo ima vi&#353;e izazova.</span></div>
                    <div><strong>Zaklju&#269;ane opcije</strong><span>neke odluke tra&#382;e dovoljno vere, mudrosti, snage ili zlata.</span></div>
                </div>
            </div>
        </div>
    </section>


    {{-- INTRO SCREEN --}}
    <section class="pv-screen pv-intro-screen hidden" id="introScreen">
        <div class="pv-cinematic">
            <div class="pv-cinematic-copy">
                <div class="pv-start-badge">Uvodna pri&#269;a</div>
                <h2 class="pv-title">Po&#269;etak vladavine</h2>
                <p class="pv-subtitle">
                    Nalazi&#353; se na po&#269;etku svoje vladavine. Tvoje odluke &#263;e oblikovati sudbinu naroda,
                    vere i dr&#382;ave. Biraj pa&#382;ljivo: svaka odluka nosi posledicu, a samo stabilna kruna
                    otklju&#269;ava zavr&#353;ni izazov.
                </p>
                <div class="pv-menu-buttons pv-intro-actions">
                    <button type="button" class="pv-btn pv-btn-primary" id="continueIntroBtn">Nastavi</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="introBackBtn">Nazad</button>
                </div>
            </div>
        </div>
    </section>
    {{-- GAME SCREEN --}}
    <section class="pv-screen pv-game-screen hidden" id="gameScreen">
        <div class="pv-topbar">
            <div class="pv-brand">
                <div class="pv-brand-title">Put vladara</div>
                <div class="pv-brand-subtitle" id="chapterLabel">Poglavlje I - Uspon</div>
            </div>

            <div class="pv-stats" id="statsBar">
                <div class="pv-stat" data-stat="snaga"><span>Snaga</span><strong id="stat-snaga">5</strong></div>
                <div class="pv-stat" data-stat="mudrost"><span>Mudrost</span><strong id="stat-mudrost">5</strong></div>
                <div class="pv-stat" data-stat="vera"><span>Vera</span><strong id="stat-vera">5</strong></div>
                <div class="pv-stat" data-stat="ugled"><span>Ugled</span><strong id="stat-ugled">5</strong></div>
                <div class="pv-stat" data-stat="zlato"><span>Zlato</span><strong id="stat-zlato">6</strong></div>
                <div class="pv-stat" data-stat="stabilnost"><span>Stabilnost</span><strong id="stat-stabilnost">5</strong></div>
                <div class="pv-stat pv-stat-turn" data-stat="turn"><span>Nivo</span><strong id="stat-turn">1: 1/3</strong></div><div class="pv-stat pv-stat-timer" data-stat="time"><span>Vreme</span><strong id="mainTimer">35:00</strong></div>
            </div>

            <div class="pv-actions">
                <button type="button" class="pv-top-btn" id="pauseBtn">Pauza</button>
                <button type="button" class="pv-top-btn" id="topFullscreenBtn">Fullscreen</button>
                <button type="button" class="pv-top-btn" id="backToMenuBtn">Meni</button>
                <button type="button" class="pv-top-btn" id="restartBtn">Ponovo</button>
            </div>
        </div>

        <div class="pv-main">
            <div class="pv-left">
                <div class="pv-character-card">
                    <img src="{{ asset('images/game/character.png') }}" alt="Vladar" id="characterImage" class="pv-character">

                    <div class="pv-character-caption">
                        <div class="pv-character-name" id="characterName">Mladi vladar</div>
                        <div class="pv-character-state" id="characterState">Sudbina dr&#382;ave je u tvojim rukama.</div>
                    </div>
                </div>
            </div>

            <div class="pv-right">
                <div class="pv-story-panel" id="storyPanel">
                    <div class="pv-scene-meta">
                        <span class="pv-scene-type" id="sceneType">Dr&#382;avni savet</span>
                        <span class="pv-progress" id="progressLabel">Nivo 1 - pitanje 1 od 3</span>
                    </div>

                    <div class="pv-objective-bar">
                        <div class="pv-objective-title">Trenutni zadatak</div>
                        <div class="pv-objective-text" id="objectiveText">
                            Odr&#382;i ravnote&#382;u izme&#273;u naroda, dvora, riznice i duhovnog autoriteta.
                        </div>
                    </div>

                    <div class="pv-warning-bar hidden" id="warningBar"></div>

                    <h2 class="pv-scene-title" id="sceneTitle">Po&#269;etak vladavine</h2>

                    <p class="pv-scene-text" id="sceneText">
                        Tvoja vladavina po&#269;inje u nemirnom vremenu. Narod o&#269;ekuje sigurnost,
                        plemstvo odlu&#269;nost, a Crkva mudrost i odgovornost.
                    </p>

                    <div class="pv-effects-preview hidden" id="effectsPreview"></div>
                    <div class="pv-choices" id="choicesBox"></div>
                </div>

                <div class="pv-log-panel">
                    <div class="pv-log-header">
                        <div class="pv-log-title">Dnevnik vladavine</div>
                        <div class="pv-log-badge" id="endingHint">Tvoj put tek po&#269;inje</div>
                    </div>
                    <div class="pv-log-list" id="gameLog"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- HELP MODAL --}}
    <section class="pv-modal hidden" id="helpModal" aria-hidden="true">
        <div class="pv-modal-card pv-help-card">
            <div class="pv-modal-badge">Pomo&#263;</div>
            <h3 class="pv-modal-title">Kako se igra</h3>
            <div class="pv-help-list">
                <p><strong>Cilj:</strong> vodi dr&#382;avu kroz 10 nivoa, sa&#269;uvaj vreme i otklju&#269;aj zavr&#353;ni escape room.</p>
                <p><strong>Odluke:</strong> svaka opcija ima posledice koje menjaju vrednosti vladara.</p>
                <p><strong>Zaklju&#269;ano:</strong> neke odluke tra&#382;e odre&#273;eni nivo mudrosti, vere, snage, ugleda ili zlata.</p>
                <p><strong>Poraz:</strong> ako stabilnost ili ugled padnu na nulu, vladavina se ru&#353;i.</p>
                <p><strong>Pismo:</strong> koristi dugmad Lat i &#1035;&#1080;&#1088; za latinicu ili &#263;irilicu.</p>
            </div>
            <div class="pv-menu-buttons pv-modal-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="closeHelpBtn">Razumem</button>
            </div>
        </div>
    </section>

    {{-- PAUSE MODAL --}}
    <section class="pv-modal hidden" id="pauseModal" aria-hidden="true">
        <div class="pv-modal-card">
            <div class="pv-modal-badge">Pauza</div>
            <h3 class="pv-modal-title">Meni igrice</h3>
            <p class="pv-modal-text">
                Mo&#382;e&#353; da nastavi&#353; partiju, uklju&#269;i&#353; fullscreen, restartuje&#353; igru ili se vrati&#353; na po&#269;etni meni.
            </p>

            <div class="pv-settings-grid">
                <label class="pv-setting-row">
                    <span>Animacije</span>
                    <input type="checkbox" id="pauseAnimToggle" checked>
                </label>

                <label class="pv-setting-row">
                    <span>Prikaz efekata odluke</span>
                    <input type="checkbox" id="pauseEffectsToggle" checked>
                </label>

                <label class="pv-setting-row">
                    <span>Upozorenja za kriti&#269;ne vrednosti</span>
                    <input type="checkbox" id="pauseWarningToggle" checked>
                </label>
            </div>

            <div class="pv-menu-buttons">
                <button type="button" class="pv-btn pv-btn-primary" id="resumeBtn">Nastavi igru</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="modalFullscreenBtn">Fullscreen</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="modalRestartBtn">Restartuj</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="modalMenuBtn">Nazad na meni</button>
            </div>
        </div>
    </section>

    {{-- END SCREEN --}}
    <section class="pv-screen pv-end-screen hidden" id="endScreen">
        <div class="pv-end-card">
            <div class="pv-end-badge" id="endBadge">Sudbina krune</div>
            <h2 class="pv-end-title" id="endTitle">Mudri vladar</h2>
            <p class="pv-end-text" id="endText">Tvoje odluke oblikovale su sudbinu dr&#382;ave.</p>

            <div class="pv-end-summary" id="endSummary"></div>
            <div class="pv-end-stats" id="endStats"></div>

            <div class="pv-end-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="continueEscapeBtn">Nastavi u zavr&#353;ni izazov</button>
                <button type="button" class="pv-btn pv-btn-primary" id="playAgainBtn">Igraj ponovo</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="endFullscreenBtn">Fullscreen</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="endMenuBtn">Nazad na meni</button>
            </div>
        </div>
    </section>
    {{-- ESCAPE UNLOCK SCREEN --}}
    <section class="pv-screen pv-unlock-screen hidden" id="unlockScreen">
        <div class="pv-unlock-card">
            <div class="pv-end-badge">Otklju&#269;an zavr&#353;ni izazov</div>
            <h2 class="pv-end-title">Tajna odaja je otvorena</h2>
            <p class="pv-end-text">
                Po&#353;to si sa&#269;uvala krunu, dobija&#353; pristup zavr&#353;nom izazovu. Cilj je da prona&#273;e&#353; Pe&#269;at nasle&#273;a i iza&#273;e&#353; iz tajne odaje. Klik&#263;e&#353; predmete u sobi, skuplja&#353; ih u inventar, koristi&#353; tragove i otklju&#269;ava&#353; slede&#263;u prostoriju.
            </p>
            <div class="pv-chest-scene" aria-hidden="true">
                <div class="pv-chest-lid"></div>
                <div class="pv-chest-body"></div>
                <div class="pv-chest-glow"></div>
            </div>
            <div class="pv-end-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="enterEscapeBtn">U&#273;i u escape room</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="unlockMenuBtn">Nazad na meni</button>
            </div>
        </div>
    </section>

    {{-- ESCAPE ROOM SCREEN --}}
    <section class="pv-screen pv-escape-screen hidden" id="escapeScreen">
        <div class="pv-escape-shell">
            <div class="pv-escape-top">
                <div>
                    <div class="pv-start-badge" id="escapeRoomLabel">Soba 1 od 3</div>
                    <h2 class="pv-escape-title" id="escapeTitle">Manastirska odaja</h2>
                </div>
                <div class="pv-escape-timer" id="escapeTimer">05:00</div>
            </div>

            <div class="pv-escape-room">
                <div class="pv-escape-clue">
                    <div class="pv-objective-title">Trag</div>
                    <p id="escapeClueText">Pro&#269;itaj trag i upi&#353;i odgovor.</p>
                </div>

                <div class="pv-riddle-card">
                    <div class="pv-scene-type" id="escapeType">Zagonetka</div>
                    <h3 id="escapeQuestion">Ko je ktitor manastira Studenica?</h3>
                    <input type="text" class="pv-riddle-input" id="escapeAnswer" autocomplete="off" placeholder="Unesi odgovor">
                    <div class="pv-escape-feedback" id="escapeFeedback" aria-live="polite"></div>
                    <div class="pv-end-actions pv-escape-actions">
                        <button type="button" class="pv-btn pv-btn-primary" id="submitEscapeBtn">Potvrdi</button>
                        <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                        <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL ESCAPE RESULT --}}
    <section class="pv-screen pv-final-screen hidden" id="finalScreen">
        <div class="pv-end-card">
            <div class="pv-end-badge" id="finalBadge">Kona&#269;ni rezultat</div>
            <h2 class="pv-end-title" id="finalTitle">&#268;estitamo!</h2>
            <p class="pv-end-text" id="finalText">Zavr&#353;ila si Put vladara i zavr&#353;ni izazov.</p>
            <div class="pv-end-summary" id="finalSummary"></div>
            <div class="pv-end-stats" id="finalStats"></div>
            <div class="pv-end-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="finalAgainBtn">Igraj ponovo</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="finalMenuBtn">Nazad na meni</button>
            </div>
        </div>
    </section>
</div>
@endsection



