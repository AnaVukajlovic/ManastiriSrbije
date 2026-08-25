<?php

namespace App\Http\Controllers;

use App\Models\CalendarDay;
use App\Support\OrthodoxCalendarHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PravoslavniCalendarController extends Controller
{
    public function index(Request $request)
    {
        $tz = config('app.timezone', 'Europe/Belgrade');

        $selected = $request->query('date')
            ? Carbon::parse($request->query('date'), $tz)
            : Carbon::now($tz);

        $monthStart = $selected->copy()->startOfMonth();
        $monthEnd   = $selected->copy()->endOfMonth();

        // Ponedeljak=1 ... Nedelja=7
        $leadingEmpty = ((int)$monthStart->dayOfWeekIso) - 1;
        $daysInMonth  = (int)$selected->daysInMonth;

        // Učitaj zapise za ceo mesec
        $rows = CalendarDay::query()
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('date')
            ->get();

        // Mapiraj po broju dana u mesecu
        $byDay = $rows->keyBy(fn ($r) => (int) Carbon::parse($r->date)->day);

        // Izabrani dan row (ako postoji)
        $dayRow = $byDay->get((int)$selected->day);

        // Navigacija meseci
        $prev = $selected->copy()->subMonthNoOverflow()->startOfMonth();
        $next = $selected->copy()->addMonthNoOverflow()->startOfMonth();

        // Spisak svih 12 meseci za brzi skok
        $monthsList = [];
        for ($m = 1; $m <= 12; $m++) {
            $mDate = Carbon::create($selected->year, $m, 1, 0, 0, 0, $tz);
            $monthsList[] = [
                'num' => $m,
                'name' => $mDate->translatedFormat('F'),
                'short' => $mDate->translatedFormat('M'),
                'date' => $mDate->toDateString(),
                'is_active' => $selected->month === $m,
            ];
        }

        // Predstojećih 7 dana
        $upcoming = CalendarDay::query()
            ->whereBetween('date', [
                $selected->toDateString(),
                $selected->copy()->addDays(6)->toDateString()
            ])
            ->orderBy('date')
            ->get();

        return view('pages.pravoslavni.modules.kalendar.index', [
            'selected'     => $selected,
            'monthStart'   => $monthStart,
            'monthEnd'     => $monthEnd,
            'leadingEmpty' => $leadingEmpty,
            'daysInMonth'  => $daysInMonth,
            'byDay'        => $byDay,
            'dayRow'       => $dayRow,
            'prev'         => $prev,
            'next'         => $next,
            'monthsList'   => $monthsList,
            'upcoming'     => $upcoming,
        ]);
    }

    public function show(string $date)
    {
        $tz = config('app.timezone', 'Europe/Belgrade');

        // Očekujemo Y-m-d iz rute
        $selected = Carbon::createFromFormat('Y-m-d', $date, $tz)->startOfDay();

        $row = CalendarDay::query()
            ->whereDate('date', $selected->toDateString())
            ->first();

        // Prev / Next dan
        $prev = $selected->copy()->subDay();
        $next = $selected->copy()->addDay();

        // Brzi spisak 7 dana od izabranog (za sidebar)
        $week = CalendarDay::query()
            ->whereBetween('date', [
                $selected->toDateString(),
                $selected->copy()->addDays(6)->toDateString()
            ])
            ->orderBy('date')
            ->get();

        return view('pages.pravoslavni.modules.kalendar.show', [
            'selected' => $selected,
            'row'      => $row,
            'prev'     => $prev,
            'next'     => $next,
            'week'     => $week,
        ]);
    }
}