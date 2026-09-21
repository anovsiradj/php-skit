<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\simlpu_web\common\components\ResizeImage.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-21
 */

abstract class ImageHelper
{
    public static function read($filename)
    {
        $image = @imagecreatefromjpeg($filename);
        if (!$image) {
            $image = @imagecreatefrompng($filename);
        }
        if (!$image) {
            $image = @imagecreatefromgif($filename);
        }
        if (!$image) {
            $image = @imagecreatefromwebp($filename);
        }
        return $image;
    }

    public static function write($image, $dstFilename, $quality = 90)
    {
        if (file_exists($dstFilename)) {
            unlink($dstFilename);
        }
        return imagejpeg($image, $dstFilename, $quality);
    }

    public static function show($image, $quality = 90)
    {
        header('content-type: image/jpeg');
        return imagejpeg($image, null, $quality);
    }

    public static function destroy($image)
    {
        return imagedestroy($image);
    }

    protected static function destSize($srcWidth, $srcHeight, $maxWidth, $maxHeight)
    {
        if ($srcWidth > $srcHeight) {
            $dst['width'] = $maxWidth;
            $dst['height'] = (int)round($srcHeight * ($maxWidth / $srcWidth));
        } elseif ($srcWidth < $srcHeight) {
            $dst['width'] = (int)round($srcWidth * ($maxHeight / $srcHeight));
            $dst['height'] = $maxHeight;
        } else {
            $side = min($maxWidth, $maxHeight);
            $dst['width'] = $side;
            $dst['height'] = $side;
        }
        return $dst;
    }

    public static function resize($image, $maxWidth, $maxHeight, $resizeIfSmaller = false)
    {
        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);

        if ($maxWidth < $srcWidth || $maxHeight < $srcHeight || $resizeIfSmaller) {
            $dst = static::destSize($srcWidth, $srcHeight, $maxWidth, $maxHeight);

            $temp = imagecreatetruecolor($dst['width'], $dst['height']);
            imagealphablending($temp, false);
            imagesavealpha($temp, true);

            imagecopyresampled(
                $temp,
                $image,
                0, 0, 0, 0,
                $dst['width'], $dst['height'],
                $srcWidth, $srcHeight
            );

            return ['image' => $temp, 'width' => $dst['width'], 'height' => $dst['height']];
        }

        return ['image' => $image, 'width' => $srcWidth, 'height' => $srcHeight];
    }

    public static function frame($image, $dstWidth, $dstHeight, $dstX, $dstY)
    {
        $frame = imagecreatetruecolor($dstWidth, $dstHeight);
        $white = imagecolorallocate($frame, 255, 255, 255);
        $border = imagecolorallocate($frame, 100, 100, 100);
        imagefill($frame, 0, 0, $white);
        imagerectangle($frame, 0, 0, $dstWidth - 1, $dstHeight - 1, $border);

        imagecopy(
            $frame,
            $image,
            $dstX,
            $dstY,
            0, 0,
            imagesx($image),
            imagesy($image)
        );

        return $frame;
    }
}
