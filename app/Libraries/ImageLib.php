<?php

namespace App\Libraries;

use finfo;
use Illuminate\Support\Facades\File;

/**
 * ImageLib
 * @package App\Libraries
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   11/03/2021
 * @version 1.0
 */
class ImageLib
{

    /**
     * get upload path
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getUploadPath()
    {
        return config('constants.upload_path');
    }

    /**
     * save base64 image to server storage
     * @param string $sBase64Image
     * @return string
     * @throws \Exception
     */
    public static function saveBase64($sBase64Image)
    {
        $sDecodedImage = self::decodeBase64($sBase64Image);
        $sFileName = self::generateFileName($sDecodedImage);
        $sUploadFileName = self::getUploadPath() . DIRECTORY_SEPARATOR . $sFileName;
        File::put($sUploadFileName, $sDecodedImage);

        return File::exists($sUploadFileName) ? $sFileName : null;
    }

    /**
     * Decode encoded base64 image
     * @param string $sBase64Image
     * @return bool|string
     */
    public static function decodeBase64($sBase64Image)
    {
        $sPattern = config('constants.base64_regex_pattern');
        $sBase64Image = preg_replace($sPattern, '', $sBase64Image);
        return base64_decode($sBase64Image);
    }

    /**
     * get file extension
     * @param string $sDecodedImage
     * @return string
     */
    public static function getFileExtension($sDecodedImage)
    {
        $oFileInfo = new finfo(FILEINFO_MIME_TYPE);
        return self::convertMimeToExt($oFileInfo->buffer($sDecodedImage));
    }

    /**
     * convert mime type to file extension name
     * @param string $sMimeType
     * @return null
     */
    public static function convertMimeToExt($sMimeType)
    {
        $aFileTypes = [
            'image/jpeg'   => 'jpg',
            'image/jpg'    => 'jpg',
            'image/png'    => 'png',
            'image/gif'    => 'gif',
            'image/x-icon' => 'ico',
            'image/bmp'    => 'bmp',
            'image/tiff'   => 'tiff',
            'image/webp'   => 'webp'
        ];
        return $aFileTypes[$sMimeType] ? : null;
    }

    /**
     * generate file name
     * @param string $sDecodedImage
     * @return string
     */
    private static function generateFileName($sDecodedImage)
    {
        $sFileExt = self::getFileExtension($sDecodedImage);
        $sDate = date('Ymd_His');
        return $sDate . '_' . uniqid() . '.' . $sFileExt;
    }

    /**
     * Convert the image into thumbnail first before converting to base64
     * @param string $sBannerImage
     * @param string $sExt
     * @param int    $iWidth
     * @param int    $iHeight
     * @param bool   $bAspectRatio
     * @return false|string
     */
    public static function convertImageSize($sBannerImage, $sExt, $iWidth, $iHeight, $bAspectRatio)
    {
        $aImageSize = getimagesize($sBannerImage);
        $iCurrentWidth = $aImageSize[0];
        $iCurrentHeight = $aImageSize[1];
        $iResolution = $iCurrentWidth / $iCurrentHeight;
        $iWholeNumber = 1;
        $bPortrait = $iWholeNumber > $iResolution;
        $bChangeHeight = $iCurrentHeight > $iHeight;
        $bChangeWidth = $iCurrentWidth > $iWidth;

        $iWidthComputed = ($bAspectRatio && !$bPortrait) ? ($iWidth * $iResolution) : $iWidth;
        $iHeightComputed = ($bAspectRatio && $bPortrait) ? ($iHeight / $iResolution) : $iHeight;

        $iNewWidth = $bChangeWidth ? $iWidthComputed : $iCurrentWidth;
        $iNewHeight = $bChangeHeight ? $iHeightComputed : $iCurrentHeight;

        $bPng = $sExt === 'png';
        $mSrc = $bPng ? @imagecreatefrompng($sBannerImage) : @imagecreatefromjpeg($sBannerImage);

        $mDst = self::getResource($mSrc, $iNewWidth, $iNewHeight, $iCurrentWidth, $iCurrentHeight);

        ob_start(); // Let's start output buffering.
        $bPng ? imagepng($mDst) : imagejpeg($mDst); // This will normally output the image, but because of ob_start(), it won't.
        $sContents = ob_get_contents(); // Instead, output above is saved to $contents
        ob_end_clean(); // End the output buffer.

        return $sContents;
    }

    /**
     * Get image resource
     * @param resource $mSrc
     * @param int      $iNewWidth
     * @param int      $iNewHeight
     * @param int      $iCurrentWidth
     * @param int      $iCurrentHeight
     * @return resource
     */
    private static function getResource($mSrc, $iNewWidth, $iNewHeight, $iCurrentWidth, $iCurrentHeight)
    {
        $mDst = @imagecreatetruecolor($iNewWidth, $iNewHeight);
        $bBlendMode = false;
        imagealphablending($mDst, $bBlendMode);
        $bSaveFlag = true;
        imagesavealpha($mDst,$bSaveFlag);
        $iRgb = 255;
        $iAlpha = 127;
        $iTransparent = imagecolorallocatealpha($mDst, $iRgb, $iRgb, $iRgb, $iAlpha);
        $iPositionX = 0;
        $iPositionY = 0;
        imagefilledrectangle($mDst, $iPositionX, $iPositionY, $iNewWidth, $iNewHeight, $iTransparent);
        imagecopyresampled($mDst, $mSrc, $iPositionX, $iPositionY, $iPositionX, $iPositionY, $iNewWidth, $iNewHeight, $iCurrentWidth, $iCurrentHeight);
        return $mDst;
    }
}
