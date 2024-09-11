<?php

namespace App\Observers;

use App\Models\Dette;
use App\Models\Archive;

class DetteObserver
{
    public function updated(Dette $dette)
    {
        if ($dette->statut === 'soldée') {
            // Programmer une tâche pour archiver la dette à minuit
            // Vous devrez configurer une tâche cron pour exécuter cette commande
            \Artisan::queue('archive:dettes-soldees');
        }
    }
}
