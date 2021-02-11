<?php

namespace Ctessier\NovaAdvancedImageField;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

trait TransformableImage
{
    /**
     * The driver library to use for transforming the image.
     *
     * This value will override the driver configured for Intervention
     * in the `config/image.php` file of the Laravel project.
     *
     * @var string|null
     */
    private $driver = null;

    /**
     * Indicates if the image is croppable.
     *
     * @var bool
     */
    private $croppable = false;

    /**
     * The fixed aspect ratio of the crop box.
     *
     * @var float
     */
    private $cropAspectRatio;

    /**
     * The maximum aspect ratio of the crop box.
     *
     * @var float
     */
    private $maxCropAspectRatio;

    /**
     * The width for the resizing of the image.
     *
     * @var int
     */
    private $width;

    /**
     * The height for the resizing of the image.
     *
     * @var int
     */
    private $height;

    /**
     * The Intervention Image instance.
     *
     * @var \Intervention\Image\Image
     */
    private $image;

    /**
     * Override the default driver to be used by Intervention for the image manipulation.
     *
     * @param string $driver
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function driver(string $driver)
    {
        if (!in_array($driver, ['gd', 'imagick'])) {
            throw new \Exception("The driver \"$driver\" is not a valid Intervention driver.");
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * Specify if the underlying image should be croppable.
     * If a numeric value is given as a first parameter, it will be used to define a fixed aspect
     * ratio for the crop box.
     *
     * @param mixed $param
     *
     * @return $this
     */
    public function croppable($param = true, $maxAspectRatio = null)
    {
        if (is_numeric($param)) {
            $this->cropAspectRatio = $param;
            $param = true;
        }

        if (is_numeric($maxAspectRatio)) {
            $this->maxCropAspectRatio = $maxAspectRatio;
        }

        $this->croppable = $param;

        return $this;
    }

    /**
     * Specify the size (width and height) the image should be resized to.
     *
     * @param int|null $width
     * @param int|null $height
     *
     * @return $this
     */
    public function resize($width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Transform the uploaded file.
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param object|null                   $cropperData
     *
     * @return void
     */
    public function transformImage(UploadedFile $uploadedFile, $cropperData)
    {
        if (!$this->croppable && !$this->width && !$this->height) {
            return;
        }

        $pathName = $uploadedFile->getPathName();

        $this->image = Image::make($pathName);

        if ($this->croppable && $cropperData) {
            $this->cropImage($cropperData);
        }

        if ($this->width || $this->height) {
            $this->resizeImage();
        }

        $this->image->save($pathName);
        $this->image->destroy();
    }

    /**
     * Crop the image.
     *
     * @param object $cropperData
     *
     * @return void
     */
    private function cropImage(object $cropperData)
    {
        $image = $this->image;
        $width = $image->width();
        $height = $image->height();
        $x = $cropperData->x;
        $y = $cropperData->y;

        if ($width < $cropperData->width + abs($x) || $x < 0 ||
            $height < $cropperData->height + abs($y) || $y < 0) {
            $canvasWidth = abs($x) + $cropperData->width;
            $canvasHeight = abs($y) + $cropperData->height;

            $bg = Image::canvas($canvasWidth, $canvasHeight);
            $bg->fill('#ffffff');

            $insertX = abs(($x - abs($x)) / 2);
            $insertY = abs(($y - abs($y)) / 2);

            $x = abs(($x + abs($x)) / 2);
            $y = abs(($y + abs($y)) / 2);

            $bg->insert($image, 'top-left', $insertX, $insertY);
            $image = $bg;
            unset($bg);
        }

        $this->image = $image->crop($cropperData->width, $cropperData->height, $x, $y);
        unset($image);
    }

    /**
     * Resize the image.
     *
     * @return void
     */
    private function resizeImage()
    {
        $this->image->resize($this->width, $this->height, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        });
    }
}
