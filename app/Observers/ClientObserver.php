<?php

namespace App\Observers;

use App\Events\ClientCreated;
use App\Models\Client;

class ClientObserver
{
    public function created(Client $client)
    {
        event(new ClientCreated($client));
    }

    /**
     * Handle the Client "updated" event.
     */
    /* public function updated(Client $client): void
    {
        //
    } */

    /**
     * Handle the Client "deleted" event.
     */
    /* public function deleted(Client $client): void
    {
        //
    } */

    /**
     * Handle the Client "restored" event.
     */
    /* public function restored(Client $client): void
    {
        //
    } */

    /**
     * Handle the Client "force deleted" event.
     */
    /* public function forceDeleted(Client $client): void
    {

    } */
}
