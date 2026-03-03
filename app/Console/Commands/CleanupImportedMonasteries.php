<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Monastery;

class CleanupImportedMonasteries extends Command
{
    protected $signature = 'cleanup:monasteries {--dry-run}';
    protected $description = 'Cleanup imported data: latinica, 1 slika placeholder, izbaci ocigledno ne-SPC';

    public function handle()
    {
        $dry = (bool) $this->option('dry-run');

        $badWords = [
            'catholic', 'roman catholic', 'katoli', 'cathédrale',
            'evangelical', 'lutheran', 'protestant', 'reformed',
            'anglican', 'methodist', 'baptist', 'pentecostal',
            'synagogue', 'mosque', 'islam', 'islamic', 'jewish',
            'jehovah', 'mormon', 'temple', 'hindu', 'buddh',
            'unitarian'
        ];

        $placeholder = 'https://placehold.co/900x600?text=Manastiri+Srbije';

        $total = Monastery::count();
        $this->info("Ukupno u bazi: {$total}");

        $deleted = 0;
        $updated = 0;
        $renamed = 0;
        $deduped = 0;

        // 1) Izbaci očigledno ne-SPC po nazivu (heuristika)
        Monastery::chunkById(300, function ($items) use (&$deleted, $badWords, $dry) {
            foreach ($items as $m) {
                $name = (string) $m->name;
                $low = mb_strtolower($name);

                foreach ($badWords as $w) {
                    if (str_contains($low, $w)) {
                        if (!$dry) $m->delete();
                        $deleted++;
                        continue 2;
                    }
                }
            }
        });

        // 2) Normalizuj latinicu + slug (stabilno) + placeholder slika
        Monastery::chunkById(300, function ($items) use (&$updated, &$renamed, $placeholder, $dry) {
            foreach ($items as $m) {
                $oldName = (string)$m->name;
                $newName = trim($this->cyrToLat($oldName));

                $changes = [];

                if ($newName !== $oldName && $newName !== '') {
                    $changes['name'] = $newName;
                    $renamed++;
                }

                // Slug uvek iz latinice (ako je prazan ili izgleda loše)
                $newSlug = Str::slug($changes['name'] ?? $m->name);
                if ($newSlug !== '' && $newSlug !== $m->slug) {
                    // pazimo na unique slug: ako postoji, dodaj -id
                    $exists = Monastery::where('slug', $newSlug)->where('id', '!=', $m->id)->exists();
                    if ($exists) $newSlug = $newSlug . '-' . $m->id;
                    $changes['slug'] = $newSlug;
                }

                if (!empty($changes)) {
                    if (!$dry) $m->update($changes);
                    $updated++;
                }

                // 1 slika svuda: ako nema sliku u monastery_images, ubaci placeholder
                if (method_exists($m, 'images')) {
                    $has = $m->images()->exists();
                    if (!$has) {
                        if (!$dry) {
                            $m->images()->create([
                                'url' => $placeholder,
                                'sort_order' => 1,
                            ]);
                        }
                    } else {
                        // ako ima slike, ali prva nema url, popravi
                        $first = $m->images()->orderBy('sort_order')->first();
                        if ($first && empty($first->url)) {
                            if (!$dry) $first->update(['url' => $placeholder]);
                        }
                    }
                }
            }
        });

        // 3) Dedup: isti slug ili isto ime → ostavi jedan (opciono, grub)
        // Ovo radimo samo po slug-u (sigurnije):
        $dups = Monastery::select('slug')
            ->whereNotNull('slug')->where('slug', '<>', '')
            ->groupBy('slug')
            ->havingRaw('count(*) > 1')
            ->pluck('slug');

        foreach ($dups as $slug) {
            $rows = Monastery::where('slug', $slug)->orderBy('id')->get();
            $keep = $rows->shift();
            foreach ($rows as $r) {
                if (!$dry) $r->delete();
                $deduped++;
            }
        }

        $this->info("Obrisano (ne-SPC heuristika): {$deleted}");
        $this->info("Preimenovano u latinicu: {$renamed}");
        $this->info("Ažurirano (name/slug): {$updated}");
        $this->info("Uklonjeni duplikati (slug): {$deduped}");

        if ($dry) {
            $this->warn("DRY-RUN: ništa nije upisano u bazu. Pokreni bez --dry-run da primeniš.");
        }

        return Command::SUCCESS;
    }

    private function cyrToLat(string $text): string
    {
        $map = [
            'Љ'=>'Lj','Њ'=>'Nj','Џ'=>'Dž','Ђ'=>'Đ','Ј'=>'J','Ћ'=>'Ć','Ч'=>'Č','Ш'=>'Š','Ж'=>'Ž',
            'љ'=>'lj','њ'=>'nj','џ'=>'dž','ђ'=>'đ','ј'=>'j','ћ'=>'ć','ч'=>'č','ш'=>'š','ж'=>'ž',
            'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','З'=>'Z','И'=>'I','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
            'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C',
            'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','з'=>'z','и'=>'i','к'=>'k','л'=>'l','м'=>'m','н'=>'n',
            'о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
        ];
        return strtr($text, $map);
    }
}
