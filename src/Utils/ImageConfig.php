<?php

namespace App\Utils;

class ImageConfig
{
    public const DEFAULT_IMAGE_FILENAME = "default.png";
    public const MAX_IMAGE_SIZE = 2 * 1024 * 1024;
    public const ALLOWED_IMAGE_MIME_TYPES = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp', 'image/webp'];
}
