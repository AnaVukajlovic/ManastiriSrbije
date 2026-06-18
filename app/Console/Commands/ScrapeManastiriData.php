<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

class ScrapeManastiriData extends Command
{
    // Komanda koju kucaš u terminalu
    protected $signature = 'scrape:manastiri';

    // Opis komande
    protected $description = 'Automatski i masovno popunjava nedostajuće podatke o svim manastirima sa sajta manastiri.rs prateći eparhije';

    public function handle()
    {
        $this->info('==================================================');
        $this->info('Započinjem masovno preuzimanje podataka za sve manastire...');
        $this->info('==================================================');

        // 1. Uzimamo sve manastire iz tabele kojima fale podaci
        $manastiri = DB::table('monasteries')
            ->whereNull('ktitor')
            ->orWhereNull('godina_izgradnje')
            ->orWhere('city', 'Nepoznato')
            ->orWhere('city', '')
            ->get();

        if ($manastiri->isEmpty()) {
            $this->info('Svi manastiri u bazi već imaju potpuno popunjene podatke!');
            return 0;
        }

        $this->info('Pronađeno je ukupno ' . $manastiri->count() . ' manastira za ažuriranje. Pokrećem proces...');

        foreach ($manastiri as $manastir) {
            $this->line("--------------------------------------------------");
            
            // Ako manastir nema upisanu eparhiju, preskačemo ga da ne pukne link
            if (empty($manastir->eparchy)) {
                $this->warn("Preskačem manastir {$manastir->name} jer nema upisanu eparhiju.");
                continue;
            }

            $this->line("Obrađujem svetinju: {$manastir->name}");

            // --- NOVI LOGIČKI DEO ZA FORMIRANJE TAČNOG URL-A ---
            // 1. Očistimo ime eparhije (npr. "Eparhija žička" -> "žička" -> "zicka")
            $eparhijaCisto = trim(str_ireplace('Eparhija', '', $manastir->eparchy));
            $eparhijaSlug = Str::slug($eparhijaCisto);

            // 2. Pravimo slug od punog imena manastira (npr. "Manastir Trnava" -> "manastir-trnava")
            $manastirSlug = Str::slug($manastir->name);

            // 3. Sastavljamo tačan URL koji odgovara strukturi sajta manastiri.rs
            $url = "https://manastiri.rs/eparhije/{$eparhijaSlug}/{$manastirSlug}/";

            try {
                // Šaljemo zahtev sa lažnim User-Agent-om
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])->timeout(12)->get($url);

                if ($response->successful()) {
                    $html = $response->body();
                    $crawler = new Crawler($html);

                    $ktitor = null;
                    $godina = null;
                    $grad = null;

                    // Prolazimo kroz tekstualne elemente i hvatamo podatke
                    $crawler->filter('p, div, li, td, span')->each(function (Crawler $node) use (&$ktitor, &$godina, &$grad) {
                        $text = trim($node->text());

                        if (preg_match('/(?:Ktitor|Osnovao|Zadužbinar):\s*(.+)/i', $text, $matches)) {
                            $ktitor = trim($matches[1]);
                        }
                        if (preg_match('/(?:Godina izgradnje|Sagrađen|Vreme nastanka|Vek):\s*(.+)/i', $text, $matches)) {
                            $godina = trim($matches[1]);
                        }
                        if (preg_match('/(?:Grad|Mesto|Lokacija|Nalazi se u):\s*(.+)/i', $text, $matches)) {
                            $grad = trim($matches[1]);
                        }
                    });

                    // Pripremamo podatke za UPDATE
                    $updateData = [];
                    if ($ktitor && is_null($manastir->ktitor)) {
                        $updateData['ktitor'] = Str::limit($ktitor, 255);
                    }
                    if ($godina && is_null($manastir->godina_izgradnje)) {
                        $updateData['godina_izgradnje'] = Str::limit($godina, 100);
                    }
                    if ($grad && ($manastir->city === 'Nepoznato' || empty($manastir->city))) {
                        $updateData['city'] = Str::limit($grad, 255);
                    }

                    // Upisujemo u bazu ako smo našli nove podatke
                    if (!empty($updateData)) {
                        DB::table('monasteries')->where('id', $manastir->id)->update($updateData);
                        $this->info("✔ Uspešno ažurirana baza za: {$manastir->name}");
                        if (isset($updateData['ktitor'])) $this->line("   [Ktitor]: " . $updateData['ktitor']);
                        if (isset($updateData['godina_izgradnje'])) $this->line("   [Godina]: " . $updateData['godina_izgradnje']);
                        if (isset($updateData['city'])) $this->line("   [Grad/Mesto]: " . $updateData['city']);
                    } else {
                        $this->warn("Stranica postoji, ali podaci nisu strukturirani na očekivan način za: {$manastir->name}");
                    }

                } else {
                    $this->error("Stranica nije pronađena (404) za: {$manastir->name}");
                    $this->line("   Pokušani URL: $url");
                }
            } catch (\Exception $e) {
                $this->error("Greška prilikom obrade manastira {$manastir->name}: " . $e->getMessage());
            }

            // Pauza od 1.5 sekunde između zahteva da nas ne blokiraju
            usleep(1500000); 
        }

        $this->info('==================================================');
        $this->info('ZAVRŠENO! Svi nedostajući podaci su uspešno uvezeni u tvoju bazu.');
        $this->info('==================================================');
        return 0;
    }
}