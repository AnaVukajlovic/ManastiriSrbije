<?php

namespace App\Support;

use App\Models\CalendarDay;
use Carbon\Carbon;

class OrthodoxCalendarHelper
{
    /**
     * Fiksni datumi crvenih slova u godini (M-d)
     */
    public static array $fixedRedLetters = [
        '01-07', // Božić
        '01-08', // Sabor Presvete Bogorodice
        '01-09', // Sveti Stefan
        '01-14', // Obrezanje Gospodnje; Sveti Vasilije Veliki
        '01-19', // Bogojavljenje
        '01-20', // Sveti Jovan Krstitelj – Jovanjdan
        '01-27', // Sveti Sava – Savindan
        '02-12', // Sveta Tri Jerarha
        '02-15', // Sretenje Gospodnje
        '04-07', // Blagovesti
        '05-06', // Đurđevdan
        '05-12', // Sveti Vasilije Ostroški
        '05-24', // Sveti Kirilo i Metodije
        '06-28', // Vidovdan
        '07-07', // Ivanjdan
        '07-12', // Petrovdan
        '08-02', // Ilindan
        '08-19', // Preobraženje Gospodnje
        '08-28', // Velika Gospojina (Uspenje Bogorodice)
        '09-11', // Usekovanje glave Svetog Jovana Krstitelja
        '09-21', // Mala Gospojina (Rođenje Bogorodice)
        '09-27', // Krstovdan (Vozdviženje)
        '10-27', // Sveta Petka (Paraskeva)
        '10-31', // Sveti Luka; Sveti Petar Cetinjski
        '11-08', // Mitrovdan (Sveti Dimitrije)
        '11-21', // Aranđelovdan (Sabor Svetog Arhangela Mihaila)
        '12-04', // Vavedenje Presvete Bogorodice
        '12-19', // Nikoljdan (Sveti Nikola)
    ];

    /**
     * Fiksni datumi crnih podebljanih slova (M-d)
     */
    public static array $fixedBoldLetters = [
        '01-02', // Sveti Ignjatije Bogonosac; Sveti Danilo II
        '01-18', // Krstovdan (Zimski)
        '01-31', // Sveti Atanasije Veliki
        '02-26', // Sveti Simeon Mirotočivi
        '03-22', // Svetih 40 mučenika – Mladenci
        '05-08', // Sveti apostol Marko – Markovdan
        '06-04', // Sveti Jovan Vladimir
        '07-13', // Sabor 12 apostola – Pavlovdan
        '07-30', // Ognjena Marija (Sveta Marina)
        '08-04', // Blaga Marija (Sveta Marija Magdalina)
        '08-08', // Trnova Petka (Prepodobnomučenica Paraskeva)
        '09-12', // Sabor Srpskih Svetitelja
        '10-14', // Pokrov Presvete Bogorodice
        '10-19', // Sveti apostol Toma – Tomindan
        '11-14', // Sveti Vrači (Kozma i Damjan)
        '11-24', // Sveti Stefan Dečanski – Mratindan
        '11-26', // Sveti Jovan Zlatousti
        '12-09', // Sveti Alimpije Stolpnik
        '12-13', // Sveti apostol Andrija Prvozvani
        '12-17', // Sveta Varvara; Sveti Jovan Damaskin
    ];

    /**
     * Pokretni praznici za 2026. godinu (Vaskršnji krug)
     */
    public static array $movable2026RedLetters = [
        '2026-04-05', // Cveti
        '2026-04-10', // Veliki Petak
        '2026-04-12', // Vaskrs
        '2026-04-13', // Vaskrsni ponedeljak
        '2026-04-14', // Vaskrsni utorak
        '2026-05-21', // Spasovdan (Vaznesenje)
        '2026-05-31', // Duhovi (Sveta Trojica)
        '2026-06-01', // Duhovski ponedeljak
        '2026-06-02', // Duhovski utorak
    ];

    public static array $movable2026BoldLetters = [
        '2026-04-04', // Lazareva Subota – Vrbica
        '2026-04-09', // Veliki Četvrtak
        '2026-04-11', // Velika Subota
    ];

    /**
     * Proveri da li je dan crveno slovo
     */
    public static function isRedLetter(Carbon $date): bool
    {
        // 1. Svaka nedelja je Vaskrsni dan (crveno slovo)
        if ($date->isSunday()) {
            return true;
        }

        // 2. Fiksni zapovedni praznici
        $md = $date->format('m-d');
        if (in_array($md, self::$fixedRedLetters, true)) {
            return true;
        }

        // 3. Pokretni zapovedni praznici (za 2026)
        $ymd = $date->format('Y-m-d');
        if (in_array($ymd, self::$movable2026RedLetters, true)) {
            return true;
        }

        return false;
    }

    /**
     * Proveri da li je dan podebljano crno slovo
     */
    public static function isBoldLetter(Carbon $date): bool
    {
        if (self::isRedLetter($date)) {
            return false; // Ako je crveno, ima prioritet
        }

        $md = $date->format('m-d');
        if (in_array($md, self::$fixedBoldLetters, true)) {
            return true;
        }

        $ymd = $date->format('Y-m-d');
        if (in_array($ymd, self::$movable2026BoldLetters, true)) {
            return true;
        }

        return false;
    }

    /**
     * Vrati stilizovane informacije o postu
     */
    public static function formatFasting(?string $fastingType): array
    {
        $type = mb_strtolower(trim((string)$fastingType));

        if ($type === '' || str_contains($type, 'nema') || str_contains($type, 'mrs') || str_contains($type, 'bez')) {
            return [
                'type' => 'mrs',
                'label' => 'Nema posta (Mrs)',
                'short' => 'Mrs',
                'icon' => '🥩',
                'badge_class' => 'fast-badge--mrs',
                'color' => '#888',
                'bg' => 'rgba(255,255,255,0.06)',
                'border' => 'rgba(255,255,255,0.15)',
                'desc' => 'Dozvoljena je sva hrana (mrsni dan).',
            ];
        }

        if (str_contains($type, 'strogi') || str_contains($type, 'suhojedenje')) {
            return [
                'type' => 'strogi',
                'label' => 'Strogi post / Na vodi',
                'short' => 'Strogi post',
                'icon' => '🕯️',
                'badge_class' => 'fast-badge--strict',
                'color' => '#f87171',
                'bg' => 'rgba(239, 68, 68, 0.15)',
                'border' => 'rgba(239, 68, 68, 0.4)',
                'desc' => 'Strogi post (uzdržavanje ili suhojedenje na vodi bez ulja).',
            ];
        }

        if (str_contains($type, 'riba') || str_contains($type, 'rib')) {
            return [
                'type' => 'riba',
                'label' => 'Post na ribi',
                'short' => 'Riba',
                'icon' => '🐟',
                'badge_class' => 'fast-badge--fish',
                'color' => '#38bdf8',
                'bg' => 'rgba(56, 189, 248, 0.15)',
                'border' => 'rgba(56, 189, 248, 0.4)',
                'desc' => 'Dozvoljena je riba, hrana spremljena na ulju i vino.',
            ];
        }

        if (str_contains($type, 'ulj') || str_contains($type, 'vino')) {
            return [
                'type' => 'ulje',
                'label' => 'Post na ulju i vinu',
                'short' => 'Ulje i vino',
                'icon' => '🫒',
                'badge_class' => 'fast-badge--oil',
                'color' => '#fbbf24',
                'bg' => 'rgba(251, 191, 36, 0.15)',
                'border' => 'rgba(251, 191, 36, 0.4)',
                'desc' => 'Dozvoljena je hrana spremljena na biljnom ulju i vino.',
            ];
        }

        if (str_contains($type, 'voda') || str_contains($type, 'vod')) {
            return [
                'type' => 'voda',
                'label' => 'Post na vodi',
                'short' => 'Na vodi',
                'icon' => '💧',
                'badge_class' => 'fast-badge--water',
                'color' => '#60a5fa',
                'bg' => 'rgba(96, 165, 250, 0.15)',
                'border' => 'rgba(96, 165, 250, 0.4)',
                'desc' => 'Hrana se priprema isključivo na vodi (bez ulja).',
            ];
        }

        if (str_contains($type, 'razresenje') || str_contains($type, 'beli') || str_contains($type, 'sir')) {
            return [
                'type' => 'beli_mrs',
                'label' => 'Beli mrs (Trapava sedmica)',
                'short' => 'Beli mrs',
                'icon' => '🥛',
                'badge_class' => 'fast-badge--dairy',
                'color' => '#e2e8f0',
                'bg' => 'rgba(226, 232, 240, 0.15)',
                'border' => 'rgba(226, 232, 240, 0.4)',
                'desc' => 'Dozvoljeni su mlečni proizvodi i jaja (bez mesa).',
            ];
        }

        return [
            'type' => 'post',
            'label' => 'Post (' . $fastingType . ')',
            'short' => $fastingType,
            'icon' => '🥖',
            'badge_class' => 'fast-badge--custom',
            'color' => '#c5a24a',
            'bg' => 'rgba(197, 162, 74, 0.15)',
            'border' => 'rgba(197, 162, 74, 0.4)',
            'desc' => 'Pravilo posta: ' . $fastingType,
        ];
    }

    /**
     * Sinhronizuj sve unose u tabeli calendar_days sa tačnim podacima za crveno i crno slovo
     */
    public static function syncDatabaseDays(): int
    {
        $days = CalendarDay::all();
        $updated = 0;

        foreach ($days as $day) {
            $carbon = Carbon::parse($day->date);
            $isRed = self::isRedLetter($carbon);
            $isBold = self::isBoldLetter($carbon);

            $day->is_red_letter = $isRed;
            $day->is_bold_letter = $isBold;
            $day->save();
            $updated++;
        }

        return $updated;
    }
}
