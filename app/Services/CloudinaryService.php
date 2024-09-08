<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function upload(string $localPath): string
    {
        $result = $this->cloudinary->uploadApi()->upload($localPath);
        return $result['secure_url'];
    }
}
