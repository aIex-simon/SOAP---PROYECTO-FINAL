<?php

namespace App\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait StorageDisk
{
    /**
     * get disk
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return Filesystem
     */
    public function getDisk(): Filesystem
    {
        return Storage::disk('public');
    }

    /**
     * put url image in storage
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $urlImage
     * @param string $pathTarget
     * @return bool|string
     */
    public function putUrlImageInStorage(string $urlImage, string $pathTarget)
    {
        if ($contentFile = file_get_contents($urlImage)) {
            $disk = $this->getDisk();

            if ($mimeType = $this->getImageMimeTypeFromUrl($urlImage)) {
                $arrExtension = [
                    'image/jpg' => 'jpg',
                    'image/png' => 'png',
                ];

                $newPathNameImage = $pathTarget . '/' .
                    Str::random(40) . '.' .
                    $arrExtension[$mimeType];

                return $disk->put(
                    $newPathNameImage,
                    $contentFile
                );
            }
        }

        return false;
    }

    /**
     * get image mimetype from url
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $urlImage
     * @return false|string
     */
    private function getImageMimeTypeFromUrl(string $urlImage)
    {
        $mimes  = [
            IMAGETYPE_JPEG => 'image/jpg',
            IMAGETYPE_PNG => 'image/png',
        ];

        if (($image_type = exif_imagetype($urlImage)) && (array_key_exists($image_type ,$mimes))) {
            return $mimes[$image_type];
        }

        return false;
    }
}
