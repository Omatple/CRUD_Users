<?php

namespace App\Utils;

class ImageHandler
{
    private static function isSizeWithinLimit(int $fileSize): bool
    {
        if ($fileSize > ImageConfig::MAX_IMAGE_SIZE) {
            $_SESSION["error_image"] = "Image size must not exceed 2MB.";
            return false;
        }
        return true;
    }

    private static function isMimeTypeAllowed(string $mimeType): bool
    {
        if (!in_array($mimeType, ImageConfig::ALLOWED_IMAGE_MIME_TYPES)) {
            $_SESSION["error_image"] = "Please upload a valid image.";
            return false;
        }
        return true;
    }

    public static function hasNoUploadError(int $errorCode): bool
    {
        return $errorCode === UPLOAD_ERR_OK;
    }

    private static function isUploadedFileValid(string $tempFilePath): bool
    {
        if (!is_uploaded_file($tempFilePath)) {
            $_SESSION["error_image"] = "This file was not uploaded via HTTP. Please try again.";
            return false;
        }
        return true;
    }

    public static function generateUniqueImagePath(string $originalFileName): string
    {
        return __DIR__ . "/../../public/img/" . uniqid() . "-" . $originalFileName;
    }

    public static function moveUploadedFile(string $tempFilePath, string $destinationPath): bool
    {
        if (!move_uploaded_file($tempFilePath, $destinationPath)) {
            $_SESSION["error_image"] = "Unable to save the image. Please try again.";
            return false;
        }
        return true;
    }

    public static function deleteImage(string $imagePath): void
    {
        $absolutePath = __DIR__ . "/../../public/img/" . basename($imagePath);
        if (basename($imagePath) !== ImageConfig::DEFAULT_IMAGE_FILENAME && file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }

    public static function isImageValid(array $imageDetails): bool
    {
        $tempFilePath = $imageDetails["tmp_name"];
        $fileSize = $imageDetails["size"];
        $mimeType = $imageDetails["type"];
        return self::isUploadedFileValid($tempFilePath) &&
            self::isMimeTypeAllowed($mimeType) &&
            self::isSizeWithinLimit($fileSize);
    }
}
