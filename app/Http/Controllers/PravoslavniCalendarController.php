<?php

namespace App\Http\Controllers;

use App\Models\CalendarDay;
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

        // Učitaj zapise za ceo mesec (sigurno radi na SQLite)
        $rows = CalendarDay::query()
            ->whereBetween('date', [$monthStart->copy()->startOfDay(), $monthEnd->copy()->endOfDay()])
            ->orderBy('date')
            ->get();

        // map po broju dana u mesecu
        $byDay = $rows->keyBy(fn ($r) => (int) $r->date->day);

        // izabrani dan row (ako postoji)
        $dayRow = $byDay->get((int)$selected->day);

        // navigacija meseci
        $prev = $selected->copy()->subMonthNoOverflow()->startOfMonth();
        $next = $selected->copy()->addMonthNoOverflow()->startOfMonth();

        // Predstojeći (7 dana) – može da bude prazno, ok
        $upcoming = CalendarDay::query()
            ->whereBetween('date', [
                $selected->copy()->startOfDay(),
                $selected->copy()->addDays(6)->endOfDay()
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
            'upcoming'     => $upcoming,
        ]);
    }

    public function show(string $date)
    {
        $tz = config('app.timezone', 'Europe/Belgrade');

        // očekujemo Y-m-d iz rute
        $selected = Carbon::createFromFormat('Y-m-d', $date, $tz)->startOfDay();

        $row = CalendarDay::query()
            ->whereDate('date', $selected->toDateString())
            ->first();

        // prev/next dan
        $prev = $selected->copy()->subDay();
        $next = $selected->copy()->addDay();

        // brzi spisak 7 dana od izabranog (za sidebar)
        $week = CalendarDay::query()
            ->whereBetween('date', [
                $selected->copy()->startOfDay(),
                $selected->copy()->addDays(6)->endOfDay()
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