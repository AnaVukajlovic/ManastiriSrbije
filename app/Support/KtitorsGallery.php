<?php

namespace App\Support;

class KtitorsGallery
{
    /**
     * Vraća sve verifikovane freske i portrete ktitora sa izvorima
     */
    public static function all(): array
    {
        return [
            'stefan-nemanja' => [
                'name' => 'Стефан Немања (Свети Симеон Мироточиви)',
                'slug' => 'stefan-nemanja',
                'title' => 'Велики жупан Рашке и родоначелник Немањића',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => '1113 – 1199',
                'fresco_location' => 'Краљева црква, Манастир Студеница (1314. год)',
                'description' => 'Аутентична фреска Светог Симеона Мироточивог у монашкој схими са свицима молитве за свој народ, насликана у Студеници.',
                'image_url' => '/images/ktitors_gallery/stefan-nemanja.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Студеница',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Fresco_of_Stefan_Nemanja_in_King%27s_church_Studenica.jpg'
            ],
            'sveti-sava' => [
                'name' => 'Свети Сава (Растко Немањић)',
                'slug' => 'sveti-sava',
                'title' => 'Први српски архиепископ и просветитељ',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => '1175 – 1236',
                'fresco_location' => 'Црква Вазнесења Господњег, Манастир Милешева (око 1225. год)',
                'description' => 'Најстарији и најаутентичнији сачувани портрет Светог Саве, рађен још за његова живота у задужбини краља Владислава.',
                'image_url' => '/images/ktitors_gallery/sveti-sava.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Милешева',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Saint_Sava,_fresco_from_Mile%C5%A1eva.jpg'
            ],
            'stefan-prvovencani' => [
                'name' => 'Стефан Првовенчани (Стефан Немањић)',
                'slug' => 'stefan-prvovencani',
                'title' => 'Првовенчани краљ српски и ктитор Жиче',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => 'око 1165 – 1228',
                'fresco_location' => 'Манастир Милешева (XIII век)',
                'description' => 'Стефан Првовенчани у свечаној владарској одори са круном. Са Светим Савом подигао је седиште српске архиепископије — Жичу.',
                'image_url' => '/images/ktitors_gallery/stefan-prvovencani.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Милешева',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Stefan_the_First-Crowned.jpg'
            ],
            'stefan-uros-i' => [
                'name' => 'Стефан Урош I Немањић',
                'slug' => 'stefan-uros-i',
                'title' => 'Српски краљ и ктитор манастира Сопоћани',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => 'око 1223 – 1277',
                'fresco_location' => 'Храм Свете Тројице, Манастир Сопоћани (око 1265. год)',
                'description' => 'Монументални ктиторски портрет краља Уроша I са моделом сопоћанске цркве коју приноси Христу и Богородици.',
                'image_url' => '/images/ktitors_gallery/stefan-uros-i.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Сопоћани',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Stefan_Uro%C5%A1_I,_Sopo%C4%87ani.jpg'
            ],
            'kralj-dragutin' => [
                'name' => 'Стефан Драгутин Немањић',
                'slug' => 'kralj-dragutin',
                'title' => 'Српски и сремски краљ (Преподобни Теоктист)',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => 'пре 1253 – 1316',
                'fresco_location' => 'Црква Светог Ахилија у Ариљу (1296. год)',
                'description' => 'Краљ Драгутин са макетом своје задужбине у Ариљу, овековечен у драгоценом рашком фрескопису крајем XIII века.',
                'image_url' => '/images/ktitors_gallery/kralj-dragutin.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Свети Ахилије',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Fresco_of_Stefan_Dragutin,_Arilje.jpg'
            ],
            'kralj-milutin' => [
                'name' => 'Стефан Урош II Милутин',
                'slug' => 'kralj-milutin',
                'title' => 'Највећи српски задужбинар и свети краљ',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => 'око 1253 – 1321',
                'fresco_location' => 'Припрата манастира Грачаница (1321. год)',
                'description' => 'Краљ Милутин у свечаном царском дивитисиону, док му небески анђели спуштају круну као праведном хришћанском владару.',
                'image_url' => '/images/ktitors_gallery/kralj-milutin.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Грачаница',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Milutin_Gracanica_Loza.jpg'
            ],
            'simonida' => [
                'name' => 'Краљица Симонида Палеолог',
                'slug' => 'simonida',
                'title' => 'Српска краљица и византијска царска принцеза',
                'category' => 'vladarke',
                'category_label' => 'Владарке и ктиторке',
                'years' => '1294 – око 1345',
                'fresco_location' => 'Северни стуб припрате, Манастир Грачаница (1321. год)',
                'description' => 'Славни грачанички портрет младе краљице Симониде са царском круном и велом, врхунац ренесансе Палеолога у Србији.',
                'image_url' => '/images/ktitors_gallery/simonida.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Грачаница',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Simonida_Gracanica_lik.jpg'
            ],
            'stefan-decanski' => [
                'name' => 'Стефан Урош III Дечански',
                'slug' => 'stefan-decanski',
                'title' => 'Српски краљ мученик и ктитор Високих Дечана',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => 'око 1276 – 1331',
                'fresco_location' => 'Манастир Високи Дечани (око 1335. год)',
                'description' => 'Ктиторски портрет светог краља Стефана Дечанског који држи модел грандиозне цркве Христа Пантократора.',
                'image_url' => '/images/ktitors_gallery/stefan-decanski.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Високи Дечани',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Stefan_Decanski_ktitor.jpg'
            ],
            'car-dusan' => [
                'name' => 'Стефан Урош IV Душан Силни',
                'slug' => 'car-dusan',
                'title' => 'Први српски цар и законодавац (Законик из 1349)',
                'category' => 'nemanjici',
                'category_label' => 'Немањићи',
                'years' => '1308 – 1355',
                'fresco_location' => 'Манастир Лесново (XIV век)',
                'description' => 'Цар Стефан Душан у пуном царском орнату са круном византијског типа, жезлом и златним лоросом.',
                'image_url' => '/images/ktitors_gallery/car-dusan.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Лесново',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Car_Du%C5%A1an,_Manastir_Lesnovo,_XIV_vek,_Makedonija.jpg'
            ],
            'carica-jelena' => [
                'name' => 'Царица Јелена (Света Јелисавета)',
                'slug' => 'carica-jelena',
                'title' => 'Српска царица, супруга цара Душана',
                'category' => 'vladarke',
                'category_label' => 'Владарке и ктиторке',
                'years' => 'око 1310 – 1374',
                'fresco_location' => 'Манастир Лесново (XIV век)',
                'description' => 'Царица Јелена приказана поред цара Душана у свечаној царској одори украшеној бисерима и драгим камењем.',
                'image_url' => '/images/ktitors_gallery/carica-jelena.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Лесново',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Car_Du%C5%A1an_i_carica_Jelena,_Manastir_Lesnovo,_XIV_vek.jpg'
            ],
            'jelena-anzujska' => [
                'name' => 'Краљица Јелена Анжујска (Света Јелена)',
                'slug' => 'jelena-anzujska',
                'title' => 'Српска краљица и ктиторка манастира Градац',
                'category' => 'vladarke',
                'category_label' => 'Владарке и ктиторке',
                'years' => 'око 1236 – 1314',
                'fresco_location' => 'Храм Свете Тројице, Манастир Сопоћани',
                'description' => 'Краљица Јелена Анжујска, супруга Уроша I и мајка Драгутина и Милутина, подигла је прелепи манастир Градац на Ибру.',
                'image_url' => '/images/ktitors_gallery/jelena-anzujska.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Сопоћани',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Serbian_Queen_Helen_of_Anjou_Nemanjic_Sopocani_Monastery.jpg'
            ],
            'knez-lazar' => [
                'name' => 'Свети Кнез Лазар Хребељановић',
                'slug' => 'knez-lazar',
                'title' => 'Свети великомученик косовски и ктитор Раванице',
                'category' => 'lazarevici',
                'category_label' => 'Лазаревићи и Бранковићи',
                'years' => 'око 1329 – 1389',
                'fresco_location' => 'Црква Вазнесења Господњег, Манастир Раваница (око 1385. год)',
                'description' => 'Ктиторски портрет кнеза Лазара који држи макету раваничког храма, насликан за његова живота у моравском стилу.',
                'image_url' => '/images/ktitors_gallery/knez-lazar.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Раваница',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Prince_Lazar_(Ravanica_Monastery).jpg'
            ],
            'kneginja-milica' => [
                'name' => 'Кнегиња Милица Хребељановић (Света Евгенија)',
                'slug' => 'kneginja-milica',
                'title' => 'Српска владарка из рода Немањића и ктиторка Љубостиње',
                'category' => 'vladarke',
                'category_label' => 'Владарке и ктиторке',
                'years' => 'око 1335 – 1405',
                'fresco_location' => 'Црква Успења Богородице, Манастир Љубостиња (око 1402. год)',
                'description' => 'Кнегиња Милица као ктиторка Љубостиње, манастира који је постао духовно уточиште српских удовица након Косовског боја.',
                'image_url' => '/images/ktitors_gallery/kneginja-milica.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Љубостиња',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Kneginja_Milica_freska.jpg'
            ],
            'stefan-lazarevic' => [
                'name' => 'Свети Деспот Стефан Лазаревић',
                'slug' => 'stefan-lazarevic',
                'title' => 'Српски деспот, законодавац и ктитор Манасије',
                'category' => 'lazarevici',
                'category_label' => 'Лазаревићи и Бранковићи',
                'years' => '1377 – 1427',
                'fresco_location' => 'Црква Свете Тројице, Манастир Манасија (Ресава, 1418. год)',
                'description' => 'Деспот Стефан у раскошној златнотканој хаљини са макетом утврђеног манастира Манасије у десној руци.',
                'image_url' => '/images/ktitors_gallery/stefan-lazarevic.jpg',
                'source_title' => 'Wikimedia Commons / Манастир Манасија',
                'source_url' => 'https://commons.wikimedia.org/wiki/File:Despot_Stefan_Lazarevi%C4%87,_Manasija.jpg'
            ]
        ];
    }
}
