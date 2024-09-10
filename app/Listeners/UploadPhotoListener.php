<?php
namespace App\Listeners;

use App\Events\ClientCreated;
use App\Jobs\UploadToCloudinaryJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Events\Dispatcher;

class UploadPhotoListener
{
    public function handle(ClientCreated $event)
    {
        $user = $event->client->user;
        if ($user && $user->photo && $user->photo_upload_status === 'pending') {
            $localPath = Storage::disk('public')->path($user->photo);
            UploadToCloudinaryJob::dispatch($user, $localPath);
        }
    }
}
