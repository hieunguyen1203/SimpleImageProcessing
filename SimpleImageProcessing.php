<?php
namespace HieuNguyen\Image;

class SimpleImageProcessing {

    var $image;
    var $image_type;

    public function load($filename) {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {

            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {

            $this->image = imagecreatefrompng($filename);
        } elseif( $this->image_type == IMAGETYPE_WEBP) {

            $this->image = imagecreatefromwebp($filename);
        }
    }
    public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);

        } elseif( $image_type == IMAGETYPE_GIF ) {

            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {

            imagepng($this->image,$filename);
        }
        if( $permissions != null) {

            chmod($filename,$permissions);
        }
    }
    public function transparency_png(){
        $background = imagecolorallocate($this->image , 0, 0, 0);
        imagecolortransparent($this->image, $background);
        imagealphablending($this->image, false);
    }

    public function grayscale_png(){
        imagefilter($this->image, IMG_FILTER_GRAYSCALE);
    }

    public function output($image_type=IMAGETYPE_JPEG) {

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);

        } elseif( $image_type == IMAGETYPE_GIF ) {

            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {

            imagepng($this->image);
        }
    }
    public function getWidth() {

        return imagesx($this->image);
    }
    public function getHeight() {

        return imagesy($this->image);
    }
    public function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = intval($this->getWidth() * $ratio);
        $this->resize($width,$height);
    }

    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = intval($this->getheight() * $ratio);
        $this->resize($width,$height);
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }

    public function resize($width,$height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    public function watermark_image($overlay){
        $watermark_type = getimagesize($overlay)[2];
        if( $watermark_type == IMAGETYPE_JPEG ) {

            $watermark = imagecreatefromjpeg($overlay);
        } elseif( $watermark_type == IMAGETYPE_GIF ) {

            $watermark = imagecreatefromgif($overlay);
        } elseif( $watermark_type == IMAGETYPE_PNG ) {

            $watermark = imagecreatefrompng($overlay);
        } elseif( $watermark_type == IMAGETYPE_WEBP) {

            $watermark = imagecreatefromwebp($overlay);
        }
        //$watermark = imagecreatefrompng($overlay);
        $X = $this->getWidth() - 20 - imagesx($watermark);
        $Y = $this->getHeight() - 20 - imagesy($watermark);
        imagecopy($this->image, $watermark, $X, $Y, 0, 0, imagesx($watermark), imagesy($watermark));
    }

}
?>