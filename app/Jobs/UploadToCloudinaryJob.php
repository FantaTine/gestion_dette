<?php

namespace App\Jobs;

use App\Services\CloudinaryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Throwable;

class UploadToCloudinaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $localImagePath;

    public function __construct(User $user, string $localImagePath)
    {
        $this->user = $user;
        $this->localImagePath = $localImagePath;
    }

    public function handle(CloudinaryService $cloudinaryService)
    {
        try {
            $cloudinaryUrl = $cloudinaryService->upload($this->localImagePath);

            $this->user->update([
                'photo' => $cloudinaryUrl,
                'photo_upload_status' => 'completed'
            ]);

            // Supprimer l'image locale si nécessaire
            // unlink($this->localImagePath);
        } catch (\Exception $e) {
            \Log::error("Échec du téléchargement vers Cloudinary pour l'utilisateur {$this->user->id}: " . $e->getMessage());
            $this->user->update(['photo_upload_status' => 'failed']);
        }
    }

    public function failed(Throwable $exception)
    {
        $this->user->update(['photo_upload_status' => 'failed']);
    }
}
