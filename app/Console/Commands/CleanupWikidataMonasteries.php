<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupWikidataMonasteries extends Command
{
    protected $signature = 'cleanup:wikidata-monasteries {--delete : Permanently delete rejected wikidata rows}';
    protected $description = 'Reject (or delete) monasteries imported from Wikidata that are not SPC';

    public function handle()
    {
        // 1) Prebroj pre
        $total = DB::table('monasteries')->count();
        $wikidata = DB::table('monasteries')
            ->whereNotNull('wikidata_qid')
            ->orWhereNotNull('wikidata_id')
            ->count();

        $this->info("Total monasteries: {$total}");
        $this->info("Wikidata-tagged rows: {$wikidata}");

        // 2) Odbaci (sakrij) wikidata koji nisu SPC
        $affected = DB::table('monasteries')
            ->where(function ($q) {
                $q->whereNotNull('wikidata_qid')
                  ->orWhereNotNull('wikidata_id');
            })
            ->where(function ($q) {
                $q->whereNull('is_spc')->orWhere('is_spc', 0);
            })
            ->update([
                'review_status' => 'rejected',
                'is_approved' => 0,
                'is_spc' => 0,
            ]);

        $this->info("Rejected wikidata non-SPC rows: {$affected}");

        // 3) Ako želiš trajno brisanje
        if ($this->option('delete')) {
            $deleted = DB::table('monasteries')
                ->where(function ($q) {
                    $q->whereNotNull('wikidata_qid')
                      ->orWhereNotNull('wikidata_id');
                })
                ->where('review_status', 'rejected')
                ->delete();

            $this->warn("Deleted rows: {$deleted}");
        }

        return Command::SUCCESS;
    }
}