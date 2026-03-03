<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Quote;
use App\Models\CalendarDay;
use App\Models\Monastery;
use App\Services\CalendarService;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Europe/Belgrade');
        $todayDate = $now->toDateString();     // 2026-02-25
        $dayOfYear = (int) $now->dayOfYear;
        $prettyDate = $now->format('d.m.Y');   // 25.02.2026

        // === Quote dana (365) ===
        $quoteQuery = Quote::query();
        if (Schema::hasColumn('quotes', 'is_active')) {
            $quoteQuery->where('is_active', 1);
        }

        $quote = null;

        if (Schema::hasColumn('quotes', 'day_of_year')) {
            $quote = (clone $quoteQuery)
                ->where('day_of_year', $dayOfYear)
                ->first();
        }

        // fallback ako nema unosa za taj dan
        if (!$quote) {
            if (Schema::hasColumn('quotes', 'weight')) {
                $quote = (clone $quoteQuery)
                    ->orderByRaw('RANDOM() * weight DESC')
                    ->first();
            } else {
                $quote = (clone $quoteQuery)->inRandomOrder()->first();
            }
        }

        // === Kalendar dana (prvo pokušaj iz baze) ===
        $cal = CalendarDay::query()
            ->whereDate('date', $todayDate)
            ->first();

        $todayCard = null;

        if ($cal) {
            $todayCard = [
                'date'    => $prettyDate,
                'feast'   => $cal->feast_name ?? '—',
                'fasting' => $this->fastingLabel($cal->fasting_type ?? null),
                'saint'   => $cal->saint_name ?? '—',
                'note'    => !empty($cal->note) ? $cal->note : null,
                'red'     => (bool) ($cal->is_red_letter ?? false),
            ];
        } else {
            // fallback: CSV
            $csv = CalendarService::getTodayData(); // vraća već mapiran format (date/feast/fasting/saint/red/note)
            if (is_array($csv)) {
                // ako CSV date nije "dd.mm.YYYY", mi ga prepišemo u pretty format radi prikaza
                $todayCard = [
                    'date'    => $prettyDate,
                    'feast'   => $csv['feast'] ?? '—',
                    'fasting' => $this->fastingLabel($csv['fasting'] ?? null),
                    'saint'   => $csv['saint'] ?? '—',
                    'note'    => $csv['note'] ?? null,
                    'red'     => (bool) ($csv['red'] ?? false),
                ];
            } else {
                // krajnji fallback: prazno
                $todayCard = [
                    'date'    => $prettyDate,
                    'feast'   => '—',
                    'fasting' => '—',
                    'saint'   => '—',
                    'note'    => null,
                    'red'     => false,
                ];
            }
        }

        // === Predlog dana: SPC + approved (ako postoje kolone) ===
        $featuredQuery = Monastery::query();

        if (Schema::hasColumn('monasteries', 'is_approved')) {
            $featuredQuery->where('is_approved', 1);
        }
        if (Schema::hasColumn('monasteries', 'is_spc')) {
            $featuredQuery->where('is_spc', 1);
        }

        $featured = $featuredQuery
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // IMPORTANT: tvoj blade je site.home
return view('home', [
  'quote' => $quote,
  'today' => $todayCard,
  'featured' => $featured,
]);
    }

    private function fastingLabel(?string $value): string
    {
        $value = trim((string) $value);

        return match ($value) {
            'voda'       => 'Post na vodi',
            'ulje'       => 'Post na ulju',
            'riba'       => 'Post na ribi',
            'strogi'     => 'Strogi post',
            'razresenje' => 'Razrešenje (bez posta)',
            'nema', ''   => 'Nema posta',
            default      => $value === '' ? 'Nema posta' : $value,
        };
    }
}