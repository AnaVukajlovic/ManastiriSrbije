@extends('layouts.site')

@section('title','Loza Nemanjića — Porodično stablo')

@section('content')
<section class="section">
  <div class="container">

    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;margin-bottom:14px;">
      <div>
        <h1 class="ps-title" style="margin:0;">Loza Nemanjića</h1>
        <p class="ps-sub" style="margin:8px 0 0;max-width:72ch;">
          Prikaz porodičnog stabla u formi dijagrama (kutije + linije). Klikni na osobu da otvoriš detalje.
        </p>
      </div>

      <a class="btn btn--ghost" href="{{ route('edukacija.index') }}">← Nazad na Edukaciju</a>
    </div>

    {{-- TREE WRAP --}}
    <div class="nem-tree-wrap">
      <div class="nem-tree-title">
        <div class="nem-tree-title__main">ЛОЗА НЕМАЊИЋА</div>
        <div class="nem-tree-title__sub">(važniji članovi dinastije)</div>
      </div>

      {{-- TREE --}}
      <div class="nem-tree">
        <ul>
          <li>
            <a class="box root" href="{{ route('ktitors.show','zavida') }}">Zavida<br><span class="box-sub">župan</span></a>

            <ul>
              <li>
                <a class="box" href="{{ route('ktitors.show','tihomir') }}">Tihomir<br><span class="box-sub">veliki župan</span></a>
              </li>

              <li>
                <a class="box" href="{{ route('ktitors.show','miroslav') }}">Miroslav<br><span class="box-sub">knez Huma</span></a>
              </li>

              <li>
                <a class="box" href="{{ route('ktitors.show','stracimir') }}">Stracimir<br><span class="box-sub">knez</span></a>
              </li>

              <li>
                <a class="box root" href="{{ route('ktitors.show','stefan-nemanja') }}">
                  Stefan Nemanja<br><span class="box-sub">veliki župan 1166–1196</span>
                </a>

                {{-- spouse example (optional) --}}
                <div class="spouses">
                  <a class="box spouse" href="{{ route('ktitors.show','ana-nemanjic') }}">Ana (Anastasija)</a>
                </div>

                <ul>
                  <li>
                    <a class="box" href="{{ route('ktitors.show','vukan') }}">Vukan<br><span class="box-sub">kralj Duklje</span></a>

                    <div class="spouses">
                      <a class="box spouse" href="{{ route('ktitors.show','vukan-zena') }}">Žena (po izvorima)</a>
                    </div>
                  </li>

                  <li>
                    <a class="box root" href="{{ route('ktitors.show','stefan-prvovencani') }}">
                      Stefan Prvovenčani<br><span class="box-sub">kralj 1217–1228</span>
                    </a>

                    <div class="spouses">
                      <a class="box spouse" href="{{ route('ktitors.show','eudokija') }}">Evdokija</a>
                      <a class="box spouse" href="{{ route('ktitors.show','ana-dandolo') }}">Ana Dandolo</a>
                    </div>

                    <ul>
                      <li>
                        <a class="box" href="{{ route('ktitors.show','stefan-radoslav') }}">
                          Stefan Radoslav<br><span class="box-sub">kralj 1228–1233</span>
                        </a>
                        <div class="spouses">
                          <a class="box spouse" href="{{ route('ktitors.show','ana-radoslav') }}">Ana</a>
                        </div>
                      </li>

                      <li>
                        <a class="box" href="{{ route('ktitors.show','stefan-vladislav') }}">
                          Stefan Vladislav<br><span class="box-sub">kralj 1234–1243</span>
                        </a>
                        <div class="spouses">
                          <a class="box spouse" href="{{ route('ktitors.show','beloslava') }}">Beloslava</a>
                        </div>
                      </li>

                      <li>
                        <a class="box root" href="{{ route('ktitors.show','stefan-uros-i') }}">
                          Stefan Uroš I<br><span class="box-sub">kralj 1243–1276</span>
                        </a>
                        <div class="spouses">
                          <a class="box spouse" href="{{ route('ktitors.show','jelena-anzujska') }}">Jelena Anžujska</a>
                        </div>

                        <ul>
                          <li>
                            <a class="box" href="{{ route('ktitors.show','stefan-dragutin') }}">
                              Stefan Dragutin<br><span class="box-sub">kralj 1276–1282</span>
                            </a>
                            <div class="spouses">
                              <a class="box spouse" href="{{ route('ktitors.show','katarina-dragutin') }}">Katarina</a>
                            </div>

                            <ul>
                              <li>
                                <a class="box" href="{{ route('ktitors.show','vladislav-ii') }}">
                                  Vladislav II<br><span class="box-sub">kralj u Sremu</span>
                                </a>
                              </li>
                            </ul>
                          </li>

                          <li>
                            <a class="box root" href="{{ route('ktitors.show','stefan-milutin') }}">
                              Stefan Milutin<br><span class="box-sub">kralj 1282–1321</span>
                            </a>
                            <div class="spouses">
                              <a class="box spouse" href="{{ route('ktitors.show','simonida') }}">Simonida</a>
                              <a class="box spouse" href="{{ route('ktitors.show','jelisaveta') }}">Jelisaveta</a>
                            </div>

                            <ul>
                              <li>
                                <a class="box root" href="{{ route('ktitors.show','stefan-decanski') }}">
                                  Stefan Uroš III (Dečanski)<br><span class="box-sub">kralj 1321–1331</span>
                                </a>
                                <div class="spouses">
                                  <a class="box spouse" href="{{ route('ktitors.show','teodora-decanski') }}">Teodora</a>
                                </div>

                                <ul>
                                  <li>
                                    <a class="box root" href="{{ route('ktitors.show','car-dusan') }}">
                                      Stefan Uroš IV (Dušan)<br><span class="box-sub">kralj/car 1331–1355</span>
                                    </a>
                                    <div class="spouses">
                                      <a class="box spouse" href="{{ route('ktitors.show','jelena-dusan') }}">Jelena</a>
                                    </div>

                                    <ul>
                                      <li>
                                        <a class="box" href="{{ route('ktitors.show','stefan-uros-v') }}">
                                          Stefan Uroš V<br><span class="box-sub">car 1355–1371</span>
                                        </a>
                                      </li>
                                    </ul>
                                  </li>

                                  <li>
                                    <a class="box" href="{{ route('ktitors.show','simeon-sinisa') }}">
                                      Simeon (Siniša)<br><span class="box-sub">car Tesalije 1359–1370</span>
                                    </a>
                                  </li>
                                </ul>
                              </li>

                              <li>
                                <a class="box" href="{{ route('ktitors.show','konstantin') }}">
                                  Konstantin<br><span class="box-sub">protivkralj 1321</span>
                                </a>
                              </li>

                            </ul>
                          </li>

                          <li>
                            <a class="box" href="{{ route('ktitors.show','predislav-sava-ii') }}">
                              Predislav (Sava II)<br><span class="box-sub">arhiepiskop</span>
                            </a>
                          </li>

                        </ul>
                      </li>

                    </ul>
                  </li>

                  <li>
                    <a class="box" href="{{ route('ktitors.show','sveti-sava') }}">
                      Rastko (Sveti Sava)<br><span class="box-sub">arhiepiskop 1219–1236</span>
                    </a>
                  </li>

                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="nem-hint">
        Tip: ako ti je stablo preširoko na manjim ekranima, koristi horizontalni scroll.
      </div>
    </div>

  </div>
</section>

{{-- INLINE CSS: diagram like your image --}}
<style>
/* wrap + title bar like the picture */
.nem-tree-wrap{
  border-radius: 18px;
  border: 1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.03);
  box-shadow: 0 18px 45px rgba(0,0,0,.35);
  overflow:hidden;
}
.nem-tree-title{
  background: rgba(197,162,74,.18);
  border-bottom: 1px solid rgba(255,255,255,.10);
  padding: 14px 16px;
  text-align:center;
}
.nem-tree-title__main{
  font-weight: 900;
  letter-spacing:.08em;
  font-size: 18px;
}
.nem-tree-title__sub{
  margin-top:4px;
  opacity:.8;
  font-size: 12px;
}

/* horizontal scroll container */
.nem-tree{
  overflow:auto;
  padding: 18px 14px 10px;
}

/* classic CSS-tree using UL/LI connectors */
.nem-tree ul{
  padding-top: 20px;
  position: relative;
  white-space: nowrap;
  transition: all .2s;
}

.nem-tree li{
  float: left;
  text-align: center;
  list-style-type: none;
  position: relative;
  padding: 20px 10px 0 10px;
}

/* connectors: horizontal line */
.nem-tree li::before,
.nem-tree li::after{
  content: '';
  position: absolute;
  top: 0;
  right: 50%;
  border-top: 2px solid rgba(255,255,255,.35);
  width: 50%;
  height: 20px;
}
.nem-tree li::after{
  right: auto;
  left: 50%;
  border-left: 2px solid rgba(255,255,255,.35);
}

/* remove connectors for single child */
.nem-tree li:only-child::after,
.nem-tree li:only-child::before{
  display: none;
}
.nem-tree li:only-child{
  padding-top: 0;
}

/* remove left connector for first, right for last */
.nem-tree li:first-child::before,
.nem-tree li:last-child::after{
  border: 0 none;
}
/* rounded corners for the top connectors */
.nem-tree li:last-child::before{
  border-right: 2px solid rgba(255,255,255,.35);
  border-radius: 0 8px 0 0;
}
.nem-tree li:first-child::after{
  border-radius: 8px 0 0 0;
}

/* vertical line down to children */
.nem-tree ul ul::before{
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  border-left: 2px solid rgba(255,255,255,.35);
  width: 0;
  height: 20px;
}

/* boxes */
.nem-tree a.box{
  display: inline-block;
  min-width: 240px;
  max-width: 260px;
  padding: 10px 12px;
  border-radius: 10px;
  border: 2px solid rgba(255,255,255,.55);
  background: rgba(0,0,0,.12);
  color: rgba(255,255,255,.95);
  text-decoration: none;
  font-weight: 800;
  line-height: 1.2;
  box-shadow: 0 10px 25px rgba(0,0,0,.35);
  transition: .18s ease;
}
.nem-tree a.box:hover{
  transform: translateY(-2px);
  border-color: rgba(197,162,74,.85);
  background: rgba(197,162,74,.10);
}
.nem-tree a.box .box-sub{
  display:block;
  margin-top:6px;
  font-weight: 650;
  font-size: 12px;
  opacity: .85;
}

/* “root” highlight */
.nem-tree a.box.root{
  border-color: rgba(197,162,74,.9);
  background: rgba(197,162,74,.12);
}

/* spouses row under a node (optional) */
.spouses{
  margin-top: 8px;
  display:flex;
  gap:8px;
  justify-content:center;
  flex-wrap:wrap;
}
.nem-tree a.box.spouse{
  min-width: 160px;
  max-width: 180px;
  font-weight: 750;
  font-size: 13px;
  border-width: 1px;
  opacity: .95;
}

/* helper note */
.nem-hint{
  padding: 10px 14px 14px;
  border-top: 1px solid rgba(255,255,255,.08);
  color: rgba(255,255,255,.70);
  font-size: 12.5px;
}

/* clear floats */
.nem-tree ul:after{
  content:"";
  display:block;
  clear:both;
}
</style>
@endsection