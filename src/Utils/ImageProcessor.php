<?php

namespace App\Utils;

class ImageProcessor
{
    private static function isSizeAllowed(int $size): bool
    {
        if ($size > ImageConstants::IMAGE_MAX_SIZE) {
            $_SESSION["error_image"] = "Size of image not must be than up that 2MB";
            return false;
        }
        return true;
    }

    private static function isTypeMime(string $type): bool
    {
        if (!in_array($type, ImageConstants::ALLOWED_MIME_TYPES)) {
            $_SESSION["error_image"] = "Please upload a image.";
            return false;
        }
        return true;
    }

    public static function isValidError(int $errorCode): bool
    {
        return $errorCode === UPLOAD_ERR_OK;
    }

    private static function isValidUpload(string $tmpName): bool
    {
        if (!is_uploaded_file($tmpName)) {
            $_SESSION["error_image"] = "This not a image upload via HTTP, try again.";
            return false;
        }
        return true;
    }

    public static function generateUrlUniqueName(string $name): string
    {
        return __DIR__ . "/../../public/img/" . uniqid() . "-" . $name;
    }

    public static function moveImage(string $tmpName, string $urlImage): bool
    {
        if (!move_uploaded_file($tmpName, $urlImage)) {
            $_SESSION["error_image"] = "Can not save image, try again.";
            return false;
        }
        return true;
    }

    public static function deleteLastImage(string $lastImage): void
    {
        $obsoluteUrl = __DIR__ . "/../../public/img/" . basename($lastImage);
        if (basename($lastImage) !== ImageConstants::IMAGE_DEFAULT_FILENAME && file_exists($obsoluteUrl)) {
            unlink($obsoluteUrl);
        }
    }

    public static function  isValidImage(array $imageData): bool
    {
        $tmpName = $imageData["tmp_name"];
        $size = $imageData["size"];
        $type = $imageData["type"];
        return self::isValidUpload($tmpName) &&
            self::isTypeMime($type) &&
            self::isSizeAllowed($size);
    }
}
