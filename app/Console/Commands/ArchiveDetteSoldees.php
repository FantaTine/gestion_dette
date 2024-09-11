<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dette;
use App\Models\Archive;

class ArchiveDetteSoldees extends Command
{
    protected $signature = 'archive:dettes-soldees';
    protected $description = 'Archive les dettes soldées';

    public function handle()
    {
        Dette::where('statut', 'soldée')->chunk(100, function ($dettes) {
            foreach ($dettes as $dette) {
                Archive::create([
                    'detteId' => $dette->id,
                    'montantDu' => $dette->montantDu,
                    'clientId' => $dette->clientId,
                    'dateSolde' => now(),
                ]);
                $dette->delete();
            }
        });
    }
}
