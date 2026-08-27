<?php

namespace App\Support;

class EducationalMedia
{
    /**
     * Svi verifikovani video sadržaji (HistoryCast & RTS)
     */
    public static function allVideos(): array
    {
        return [
            'POvrLJW70y0' => [
                'id' => 'POvrLJW70y0',
                'title' => 'Studenica | HistoryCast nedeljom',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=POvrLJW70y0&t=5040s',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/POvrLJW70y0?start=5040',
                'description' => 'Detaljna emisija posvećena manastiru Studenica, zadužbini Stefana Nemanje, njenoj istoriji, arhitekturi i freskama.',
                'tag' => 'Manastir Studenica',
                'badge' => '🏛️ Manastir Studenica',
                'monasteries' => ['studenica'],
                'ktitors' => ['stefan-nemanja'],
                'modules' => ['manastiri-kao-zaduzbine', 'legendarijum-price'],
            ],
            '2H5EUauYV3s' => [
                'id' => '2H5EUauYV3s',
                'title' => 'Manastir Visoki Dečani | HistoryCast nedeljom',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=2H5EUauYV3s',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/2H5EUauYV3s',
                'description' => 'Istraživanje velelepnog manastira Visoki Dečani, njegove monumentalne lepote, riznice i zadužbinara Stefana Dečanskog.',
                'tag' => 'Visoki Dečani',
                'badge' => '🏛️ Visoki Dečani',
                'monasteries' => ['visoki-decani', 'decani'],
                'ktitors' => ['stefan-decanski', 'car-dusan'],
                'modules' => ['manastiri-kao-zaduzbine', 'legendarijum-price'],
            ],
            '_UIkKAFKqpk' => [
                'id' => '_UIkKAFKqpk',
                'title' => 'Žiča | HistoryCast četvrtkom, ep.03',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=_UIkKAFKqpk',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/_UIkKAFKqpk',
                'description' => 'Priča o manastiru Žiči, sedištu prve srpske autokefalne arhiepiskopije i krunidbenom mestu srpskih kraljeva.',
                'tag' => 'Manastir Žiča',
                'badge' => '👑 Sedmovrata Žiča',
                'monasteries' => ['zica'],
                'ktitors' => ['stefan-prvovencani', 'sveti-sava'],
                'modules' => ['srpska-crkva', 'legendarijum-price'],
            ],
            'MBj_g2KY09c' => [
                'id' => 'MBj_g2KY09c',
                'title' => 'Stefan Nemanja | HistoryCast, ep. 42',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=MBj_g2KY09c',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/MBj_g2KY09c',
                'description' => 'Istorijska analiza života, vladavine i zadužbinarskog dela rodonačelnika dinastije Nemanjića — Stefana Nemanje (Svetog Simeona).',
                'tag' => 'Stefan Nemanja',
                'badge' => '👑 Stefan Nemanja',
                'monasteries' => ['studenica', 'djurdjevi-stupovi'],
                'ktitors' => ['stefan-nemanja'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'c537nsIMJO8' => [
                'id' => 'c537nsIMJO8',
                'title' => 'Stefan Prvovenčani | HistoryCast, ep. 75',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=c537nsIMJO8&t=2371s',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/c537nsIMJO8?start=2371',
                'description' => 'Epizoda o prvom krunisanom srpskom kralju Stefanu Prvovenčanom, njegovom savezu sa Svetim Savom i uzdizanju kraljevstva.',
                'tag' => 'Stefan Prvovenčani',
                'badge' => '👑 Stefan Prvovenčani',
                'monasteries' => ['zica'],
                'ktitors' => ['stefan-prvovencani'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'QkZvTqTBYsk' => [
                'id' => 'QkZvTqTBYsk',
                'title' => 'Sveti Sava, prvi srpski zakonodavac | HistoryCast ep. 123',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=QkZvTqTBYsk',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/QkZvTqTBYsk',
                'description' => 'Kako je Sveti Sava kroz Nomokanon (Zakonopravilo) postavio temelje srpskog pravnog poretka, prosvete i duhovnog života.',
                'tag' => 'Sveti Sava',
                'badge' => '🕊️ Sveti Sava',
                'monasteries' => ['hilandar', 'studenica', 'zica', 'mileseva'],
                'ktitors' => ['sveti-sava'],
                'modules' => ['srpska-crkva', 'legendarijum-price'],
            ],
            'FIabk7w6erc' => [
                'id' => 'FIabk7w6erc',
                'title' => 'Kralj Uroš Veliki | HistoryCast, ep. 49',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=FIabk7w6erc',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/FIabk7w6erc',
                'description' => 'Doba ekonomskog procvata, dolaska rudara Sasa, gradnje Sopoćana i vladavine Stefana Uroša I Velikog.',
                'tag' => 'Stefan Uroš I',
                'badge' => '👑 Stefan Uroš I',
                'monasteries' => ['sopocani', 'gradac'],
                'ktitors' => ['stefan-uros-i', 'jelena-anzujska', 'kralj-dragutin'],
                'modules' => ['istorija-kultura', 'manastiri-kao-zaduzbine'],
            ],
            '5e7-GpgcwFw' => [
                'id' => '5e7-GpgcwFw',
                'title' => 'Kralj Milutin | HistoryCast ep.56',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=5e7-GpgcwFw',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/5e7-GpgcwFw',
                'description' => 'Najveći zadužbinar među Nemanjićima — 40 godina vladavine i više od 40 podignutih svetinja širom Balkana i Svete Zemlje.',
                'tag' => 'Kralj Milutin',
                'badge' => '👑 Kralj Milutin',
                'monasteries' => ['gracanica', 'bogorodica-ljeviska', 'banjska', 'studenica'],
                'ktitors' => ['kralj-milutin', 'simonida'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'W5uSGweGQ_0' => [
                'id' => 'W5uSGweGQ_0',
                'title' => 'Stefan Dečanski | HistoryCast, ep. 96',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=W5uSGweGQ_0',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/W5uSGweGQ_0',
                'description' => 'Život kralja mučenika, od iskušenja i oslepljenja do pobede na Velbuždu i podizanja manastira Dečani.',
                'tag' => 'Stefan Dečanski',
                'badge' => '👑 Stefan Dečanski',
                'monasteries' => ['visoki-decani', 'decani'],
                'ktitors' => ['stefan-decanski', 'car-dusan'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'fS7sCNBUEPE' => [
                'id' => 'fS7sCNBUEPE',
                'title' => 'Uspon carstva: Dušanova Srbija | HistoryCast',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=fS7sCNBUEPE',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/fS7sCNBUEPE',
                'description' => 'Vrhunac vojne i političke moći srednjovekovne Srbije u vreme krunisanja Stefana Dušana za cara Srba i Grka.',
                'tag' => 'Car Dušan',
                'badge' => '⚔️ Dušanovo Carstvo',
                'monasteries' => ['sveti-arhangeli-prizren'],
                'ktitors' => ['car-dusan', 'carica-jelena', 'uros-nejaki'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'noVmoibvr5I' => [
                'id' => 'noVmoibvr5I',
                'title' => 'Dušanov zakonik | HistoryCast, ep. 68',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=noVmoibvr5I',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/noVmoibvr5I',
                'description' => 'Analiza najvažnijeg pravnog spomenika srpskog srednjeg veka usvojenog u Skoplju 1349. i dopunjenog u Seru 1354.',
                'tag' => 'Dušanov zakonik',
                'badge' => '📜 Dušanov zakonik',
                'monasteries' => [],
                'ktitors' => ['car-dusan', 'carica-jelena'],
                'modules' => ['istorija-kultura', 'ucenje-interakcija'],
            ],
            'siBbv7PKXFM' => [
                'id' => 'siBbv7PKXFM',
                'title' => 'Uroš Nejaki - Car Stefan Uroš V | HistoryCast, ep. 115',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=siBbv7PKXFM',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/siBbv7PKXFM',
                'description' => 'Priča o poslednjem vladaru dinastije Nemanjića, raspadu carstva i sudbonosnim godinama pred Maričku bitku.',
                'tag' => 'Uroš Nejaki',
                'badge' => '👑 Car Uroš V',
                'monasteries' => [],
                'ktitors' => ['uros-nejaki', 'carica-jelena'],
                'modules' => ['istorija-kultura', 'legendarijum-price'],
            ],
            'gNik86s7dKA' => [
                'id' => 'gNik86s7dKA',
                'title' => 'Knez Lazar | HistoryCast, ep. 43',
                'author' => 'HistoryCast',
                'url' => 'https://www.youtube.com/watch?v=gNik86s7dKA',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/gNik86s7dKA',
                'description' => 'Epska priča o knezu Lazaru Hrebeljanoviću, obnovi moravske Srbije, zidanju Ravanice i kosovskom opredeljenju.',
                'tag' => 'Knez Lazar',
                'badge' => '⚔️ Knez Lazar',
                'monasteries' => ['ravanica', 'lazarica', 'ljubostinja', 'manasija'],
                'ktitors' => ['knez-lazar', 'kneginja-milica', 'stefan-lazarevic'],
                'modules' => ['srbija-pod-osmanlijama', 'legendarijum-price'],
            ],
            'KjsUmoQukSY' => [
                'id' => 'KjsUmoQukSY',
                'title' => 'Putevi srednjeg veka: Nemanjići (specijalno izdanje)',
                'author' => 'RTS Kulturno-umetnički program',
                'url' => 'https://www.youtube.com/watch?v=KjsUmoQukSY',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/KjsUmoQukSY',
                'description' => 'Celovit televizijski dokumentarac koji vodi kroz zadužbine, putovanja i duhovno nasleđe dinastije Nemanjića.',
                'tag' => 'Nemanjići',
                'badge' => '✨ Specijalno izdanje',
                'monasteries' => ['studenica', 'zica', 'sopocani', 'mileseva', 'djurdjevi-stupovi'],
                'ktitors' => ['stefan-nemanja', 'sveti-sava', 'stefan-prvovencani', 'vukan-nemanjic', 'stefan-radoslav', 'stefan-vladislav', 'kralj-dragutin', 'ana-zena-stefana-nemanje', 'ana-dandolo'],
                'modules' => ['porodicno-stablo', 'legendarijum-price', 'ucenje-interakcija'],
            ],
            'esbOKOPuecU' => [
                'id' => 'esbOKOPuecU',
                'title' => 'TV Feljton: Kralj Milutin i Kraljica Simonida',
                'author' => 'RTS Kulturno-umetnički program',
                'url' => 'https://www.youtube.com/watch?v=esbOKOPuecU',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/esbOKOPuecU',
                'description' => 'Eminentna dokumentarna emisija RTS-a o kralju Milutinu, vizantijskoj princezi i srpskoj kraljici Simonidi, i njihovim zadužbinama.',
                'tag' => 'Kraljica Simonida',
                'badge' => '👑 Kraljica Simonida',
                'monasteries' => ['gracanica', 'studenica', 'banjska'],
                'ktitors' => ['simonida', 'kralj-milutin'],
                'modules' => ['porodicno-stablo', 'legendarijum-price', 'manastiri-kao-zaduzbine', 'istorija-kultura'],
            ],
            'NFu8SFXl_DY' => [
                'id' => 'NFu8SFXl_DY',
                'title' => 'Kvadratura kruga: Nemanjići — zadužbine i poreklo',
                'author' => 'RTS Kvadratura kruga',
                'url' => 'https://www.youtube.com/watch?v=NFu8SFXl_DY',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/NFu8SFXl_DY',
                'description' => 'Autorska emisija Branka Stankovića o poreklu Nemanjića, njihovim zadužbinama i trajnom uticaju na srpski identitet.',
                'tag' => 'Nemanjići',
                'badge' => '📜 Kvadratura kruga',
                'monasteries' => ['studenica', 'zica', 'djurdjevi-stupovi', 'mileseva', 'sopocani'],
                'ktitors' => ['stefan-nemanja', 'sveti-sava', 'stefan-prvovencani', 'vukan-nemanjic', 'stefan-radoslav', 'stefan-vladislav', 'stefan-uros-i', 'kralj-dragutin'],
                'modules' => ['porodicno-stablo', 'istorija-kultura', 'manastiri-kao-zaduzbine', 'legendarijum-price'],
            ],
            '6jVWFkcsplk' => [
                'id' => '6jVWFkcsplk',
                'title' => 'Putevi srednjeg veka: Veliki župan Stefan Nemanja',
                'author' => 'RTS Kulturno-umetnički program',
                'url' => 'https://www.youtube.com/watch?v=6jVWFkcsplk',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/6jVWFkcsplk',
                'description' => 'Dokumentarni serijal o osnivaču dinastije Stefanu Nemanji, ujedinjenju srpskih zemalja i građenju Studenice i Hilandara.',
                'tag' => 'Stefan Nemanja',
                'badge' => '🏛️ Putevi srednjeg veka',
                'monasteries' => ['studenica', 'djurdjevi-stupovi'],
                'ktitors' => ['stefan-nemanja', 'ana-zena-stefana-nemanje', 'vukan-nemanjic'],
                'modules' => ['istorija-kultura', 'timeline', 'legendarijum-price'],
            ],
            'pJRlulXe3lI' => [
                'id' => 'pJRlulXe3lI',
                'title' => 'Borbe Milutina sa Bugarima i Tatarima - Uspon Dragutina',
                'author' => 'Srpske Bitke',
                'url' => 'https://www.youtube.com/watch?v=pJRlulXe3lI',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/pJRlulXe3lI',
                'description' => 'Istorijska i strateška analiza sukoba na kraju 13. veka, borbe kralja Milutina sa Bugarima i Tatarima, i vladavine kralja Dragutina.',
                'tag' => 'Srpske Bitke',
                'badge' => '⚔️ Srpske Bitke',
                'monasteries' => ['djurdjevi-stupovi', 'banjska', 'gracanica'],
                'ktitors' => ['kralj-dragutin', 'kralj-milutin', 'stefan-uros-i'],
                'modules' => ['istorija-kultura'],
            ],
            '_W6g0DQAjBE' => [
                'id' => '_W6g0DQAjBE',
                'title' => 'Serbian King Milutin Destroys Tatars - Battle of River Drim',
                'author' => 'Srpske Bitke',
                'url' => 'https://www.youtube.com/watch?v=_W6g0DQAjBE',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/_W6g0DQAjBE',
                'description' => 'Istorijska rekonstrukcija bitke na reci Drim i vojnih uspeha kraljeva Milutina i Dragutina u odbrani srpskih zemalja.',
                'tag' => 'Srpske Bitke',
                'badge' => '⚔️ Srpske Bitke',
                'monasteries' => ['banjska', 'gracanica', 'hilandar'],
                'ktitors' => ['kralj-milutin', 'kralj-dragutin'],
                'modules' => ['istorija-kultura'],
            ],
            'pbJQh7G1UQ4' => [
                'id' => 'pbJQh7G1UQ4',
                'title' => 'Srpski dug Hristu - Sv. Milutin, Sv. Dragutin, Sv. Jelena',
                'author' => 'TV Hram',
                'url' => 'https://www.youtube.com/watch?v=pbJQh7G1UQ4',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/pbJQh7G1UQ4',
                'description' => 'Duhovna i istorijska emisija TV Hram o svetoj porodici Nemanjića: kralju Milutinu, kralju Dragutinu i njihovoj majci kraljici Jeleni Anžujskoj.',
                'tag' => 'Sveti Nemanjići',
                'badge' => '🕊️ TV Hram',
                'monasteries' => ['gradac', 'djurdjevi-stupovi', 'arilje', 'banjska', 'gracanica'],
                'ktitors' => ['kralj-dragutin', 'kralj-milutin', 'jelena-anzujska'],
                'modules' => ['istorija-kultura', 'srpska-crkva'],
            ],
            'ztCa-CRig08' => [
                'id' => 'ztCa-CRig08',
                'title' => 'Čas istorije - Kralj Stefan Uroš II Milutin',
                'author' => 'TV Hram',
                'url' => 'https://www.youtube.com/watch?v=ztCa-CRig08&t=425s',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/ztCa-CRig08?start=425',
                'description' => 'Predavanje o dugoj i uspešnoj vladavini kralja Milutina, njegovim zadužbinama, odnosima sa bratom Dragutinom i majkom Jelenom.',
                'tag' => 'Kralj Milutin',
                'badge' => '🕊️ TV Hram',
                'monasteries' => ['banjska', 'gracanica', 'djurdjevi-stupovi', 'studenica'],
                'ktitors' => ['kralj-milutin', 'kralj-dragutin', 'jelena-anzujska', 'simonida'],
                'modules' => ['istorija-kultura'],
            ],
        ];
    }

    /**
     * Vrati video sadržaje za dati manastir (bez duplikata)
     */
    public static function forMonastery(string $slug): array
    {
        $all = self::allVideos();
        $res = [];
        foreach ($all as $id => $v) {
            if (in_array($slug, $v['monasteries'], true)) {
                $res[$id] = $v;
            }
        }
        return array_values($res);
    }

    /**
     * Vrati video sadržaje za datog ktitora (bez duplikata)
     */
    public static function forKtitor(string $slug): array
    {
        $all = self::allVideos();
        $res = [];
        foreach ($all as $id => $v) {
            if (in_array($slug, $v['ktitors'], true)) {
                $res[$id] = $v;
            }
        }
        return array_values($res);
    }

    /**
     * Vrati video sadržaje za obrazovni modul (bez duplikata)
     */
    public static function forEduModule(string $slug): array
    {
        $all = self::allVideos();
        $res = [];
        foreach ($all as $id => $v) {
            if (in_array($slug, $v['modules'], true)) {
                $res[$id] = $v;
            }
        }
        return array_values($res);
    }

    /**
     * Vrati sve video sadržaje o dinastiji Nemanjića u hronološkom redosledu
     */
    public static function forNemanjici(): array
    {
        $all = self::allVideos();
        $orderedIds = [
            'KjsUmoQukSY', // Putevi srednjeg veka: Nemanjići (RTS)
            'NFu8SFXl_DY', // Kvadratura kruga: Nemanjići — zadužbine i poreklo (RTS)
            '6jVWFkcsplk', // Putevi srednjeg veka: Stefan Nemanja (RTS)
            'MBj_g2KY09c', // Stefan Nemanja (HistoryCast)
            'POvrLJW70y0', // Studenica (HistoryCast)
            'QkZvTqTBYsk', // Sveti Sava (HistoryCast)
            'c537nsIMJO8', // Stefan Prvovenčani (HistoryCast)
            '_UIkKAFKqpk', // Žiča (HistoryCast)
            'FIabk7w6erc', // Kralj Uroš Veliki (HistoryCast)
            'pbJQh7G1UQ4', // Srpski dug Hristu - Milutin, Dragutin, Jelena (TV Hram)
            'pJRlulXe3lI', // Borbe Milutina - Uspon Dragutina (Srpske Bitke)
            '5e7-GpgcwFw', // Kralj Milutin (HistoryCast)
            '_W6g0DQAjBE', // Serbian King Milutin Destroys Tatars (Srpske Bitke)
            'ztCa-CRig08', // Čas istorije - Kralj Milutin (TV Hram)
            'esbOKOPuecU', // Kralj Milutin i Kraljica Simonida (RTS)
            'W5uSGweGQ_0', // Stefan Dečanski (HistoryCast)
            '2H5EUauYV3s', // Visoki Dečani (HistoryCast)
            'fS7sCNBUEPE', // Uspon carstva: Dušanova Srbija (HistoryCast)
            'noVmoibvr5I', // Dušanov zakonik (HistoryCast)
            'siBbv7PKXFM', // Uroš Nejaki — Car Stefan Uroš V (HistoryCast)
        ];

        $res = [];
        foreach ($orderedIds as $id) {
            if (isset($all[$id])) {
                $res[] = $all[$id];
            }
        }
        return $res;
    }
}
