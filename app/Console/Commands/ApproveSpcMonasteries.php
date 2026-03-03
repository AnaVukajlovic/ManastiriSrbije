<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApproveSpcMonasteries extends Command
{
    protected $signature = 'monasteries:approve-spc {--dry-run : Show how many rows would be updated}';
    protected $description = 'Approve all monasteries marked as SPC (is_spc=1)';

    public function handle()
    {
        $q = DB::table('monasteries')->where('is_spc', 1);

        $count = (clone $q)->count();

        if ($this->option('dry-run')) {
            $this->info("DRY RUN: would approve {$count} SPC monasteries.");
            return Command::SUCCESS;
        }

        $updated = $q->update([
            'is_approved' => 1,
            'review_status' => 'approved',
        ]);

        $this->info("✅ Approved SPC monasteries: {$updated} (matched: {$count})");
        return Command::SUCCESS;
    }
}