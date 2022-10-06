<?php

namespace App\Model\Image;

use App\Config\StaticConfigs;

class Image
{
    private array $sizes = array(
        400,
        540,
        720,
        960,
        1140,
        1440,
        1920
    );
    public const UNKNOWN_IMG = "unknown.png";

    public const WATERMARK = 'water.png';

    /**
     * @param string $path
     * @return array [
     *      ['img' => filename, 'size' => size]
     * ]
     */
    public function loadImages(?string $path) :array{
        $imagesRes = [];
        if(!empty($path) && is_dir(realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$path)){
            $images = scandir(realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$path);
            foreach($images as $image){
                if($image == '.' || $image == '..')
                    continue;
                $size = $this->get_size_between($image);
                $imagesRes[] = [
                    'img'   => $path.DS.$image,
                    'size'  => $size
                ];
            }
        }
        if(empty($imagesRes))
            $imagesRes[] = [
                'img'   => self::UNKNOWN_IMG,
                'size'  => 'org'
            ];
        return $imagesRes;
    }

    public function deleteImages(string $path) :bool{
        if(is_dir(realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$path)) {
            $files = glob(realpath(StaticConfigs::SAVE_IMAGE_DIR) . DS . $path . '/*');
            foreach ($files as $file) {
                if (is_file($file))
                    // Delete the given file
                    unlink($file);
            }
            rmdir(realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$path);
        }
        return true;
    }

    private function get_size_between($filename){
        $string = ' ' . $filename;
        $ini = strpos($string, '-');
        if ($ini == 0) return '';
        $ini += strlen('-');
        $len = strpos($string, '.', $ini) - $ini;
        return substr($string, $ini, $len);
    }
    /**
     * @param array $file
     * @param string $filename
     * @param string $saveDir
     * @description: converts a image to all sizes specified in $size which are under the original size
     * @return array [
     *      ...,
     *      [
     *          'path' => saveDir of image (server),
     *          'url'  => url path of image (everything after the uploadDir)
     *          'size' => size (width) in px of the image
     *      ],
     *      ...
     * ]
     */
    public function convert(array $file, string $filename, string $saveDir) :array{
        $orgImgInfo = getimagesize($file['tmp_name']);
        $orgImageWidth = $orgImgInfo[0];
        $orgImageHeight = $orgImgInfo[1];
        $maxWidth = $this->getClosestWidth($orgImageWidth);
        $imageType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        switcH($imageType){
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'gif':
                $image = imagecreatefromgif($file['tmp_name']);
                break;
            case 'png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
        }

        // add Water Trate Mark

        $res = [];
        if(!empty($image)) {
            // add water trate mark
            $this->addWaterMark($image);
            foreach ($this->sizes as $width) {
                if($width <= $maxWidth) {
                    $newImg = $this->resizeImageToWidth($width, $image, $orgImageWidth, $orgImageHeight, $imageType);
                    $path = realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$saveDir.DS.$filename.'-'.$width.'.'.$imageType;
                    $this->saveImage($newImg, $path, $imageType, 100);
                    $res[] = [
                        'path'  => $path,
                        'url'   => $saveDir.DS.$filename.'-'.$width.'.'.$imageType,
                        'size'  => $width
                    ];
                }
            }

            // save original
            $path = realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.$saveDir.DS.$filename.'-org.'.$imageType;
            $image = $this->resizeImageToWidth($maxWidth ?? $orgImageWidth, $image, $orgImageWidth, $orgImageHeight, $imageType);
            $this->saveImage($image, $path, $imageType, 100);
        }
        return $res;
    }

    private function addWaterMark(&$image){

        $stamp = imagecreatefrompng(realpath(StaticConfigs::SAVE_IMAGE_DIR).DS.self::WATERMARK);

        // set position of tratemark
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // place tratemark on image
        imagecopy(
            $image,
            $stamp,
            imagesx($image) - $sx - $marge_right,
            imagesy($image) - $sy - $marge_bottom,
            0,
            0,
            imagesx($stamp),
            imagesy($stamp)
        );
    }

    private function getClosestWidth($search) {
        $closest = null;
        foreach($this->sizes as $size){
            if($closest < $size && $size < $search)
                $closest = $size;
        }
        return $closest;
    }
    private function resizeImageToWidth($new_width, $image, $width, $height, $imageType) {
        $resize_ratio   = $new_width / $width;
        $new_height     = $height * $resize_ratio;
        return $this->resizeImage($new_width, $new_height, $image, $width, $height, $imageType);
    }
    private function resizeImage($new_width, $new_height, $image, $width, $height, $imageType) {
        $new_image = imagecreatetruecolor($new_width, $new_height);
        if ($imageType === 'png' || $imageType === 'gif') {
            imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
        }
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        return $new_image;
    }
    private function saveImage($new_image, $new_filename, $new_type='jpeg', $quality=80) {
        if( $new_type == 'jpeg' || $new_type == 'jpg' ) {
            imagejpeg($new_image, $new_filename, $quality);
        }
        elseif( $new_type == 'png' ) {
            imagepng($new_image, $new_filename);
        }
        elseif( $new_type == 'gif' ) {
            imagegif($new_image, $new_filename);
        }
    }

    public static function checkImg(?array $img, bool $required = false) :bool{
    if(!empty($img)){
        $check          = getimagesize($img["tmp_name"]);
        $fileExtension  = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
        if(
            $check === false || $img['size'] > StaticConfigs::MAX_IMAGE_SIZE ||
            !in_array($fileExtension, StaticConfigs::ALLOWED_IMAGE_EXTENSION)
        )
            return false;
        return true;
    }
    return !$required;
}

}
