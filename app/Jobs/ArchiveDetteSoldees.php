<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Dette;
use App\Models\ArchiveDette;

class ArchiveDetteSoldees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $detteSoldees = Dette::where('statut', 'soldée')->with('articles', 'paiements')->get();

        foreach ($detteSoldees as $dette) {
            ArchiveDette::create([
                'original_id' => $dette->id,
                'montantDu' => $dette->montantDu,
                'clientId' => $dette->clientId,
                'dateSolde' => now(),
                'articles' => $dette->articles->toArray(),
                'paiements' => $dette->paiements->toArray(),
            ]);

            $dette->delete();
        }
    }
}
