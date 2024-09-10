<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Observers\ClientObserver;
use App\Events\ClientCreated;
use App\Listeners\UploadPhotoListener;
use App\Listeners\MailListener;
use Illuminate\Support\Facades\Event;

class InitializeAppComponents
{
    public function handle(Request $request, Closure $next)
    {
        // Enregistrer l'observer
        Client::observe(ClientObserver::class);

        // Enregistrer les listeners
        Event::listen(ClientCreated::class, [UploadPhotoListener::class, 'handle']);
        Event::listen(ClientCreated::class, [MailListener::class, 'handle']);

        return $next($request);
    }
}
