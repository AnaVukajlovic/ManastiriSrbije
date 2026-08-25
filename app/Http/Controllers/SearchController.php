<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $activeTab = trim((string) $request->query('type', 'all'));

        if (!in_array($activeTab, ['all', 'monasteries', 'ktitors', 'calendar', 'topics', 'curiosities'], true)) {
            $activeTab = 'all';
        }

        $terms = SearchService::getSearchTerms($q);

        $monasteries = collect();
        $ktitors = collect();
        $calendarDays = collect();
        $topics = collect();
        $curiosities = collect();

        if (!empty($terms)) {
            $monasteries = SearchService::searchMonasteries($terms, $q);
            $ktitors = SearchService::searchKtitors($terms);
            $calendarDays = SearchService::searchCalendarDays($terms);
            $topics = SearchService::searchTopics($q, $terms);
            $curiosities = SearchService::searchCuriosities($terms);
        }

        $counts = [
            'all'         => $monasteries->count() + $ktitors->count() + $calendarDays->count() + $topics->count() + $curiosities->count(),
            'monasteries' => $monasteries->count(),
            'ktitors'     => $ktitors->count(),
            'calendar'    => $calendarDays->count(),
            'topics'      => $topics->count(),
            'curiosities' => $curiosities->count(),
        ];

        return view('pages.search.index', [
            'q'            => $q,
            'activeTab'    => $activeTab,
            'counts'       => $counts,
            'monasteries'  => $monasteries,
            'ktitors'      => $ktitors,
            'calendarDays' => $calendarDays,
            'topics'       => $topics,
            'curiosities'  => $curiosities,
        ]);
    }
}
