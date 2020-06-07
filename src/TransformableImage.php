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
    public function croppable($param = true)
    {
        if (is_numeric($param)) {
            $this->cropAspectRatio = $param;
            $param = true;
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

        $this->image = Image::make($uploadedFile->getPathName());

        if ($this->croppable && $cropperData) {
            $this->cropImage($cropperData);
        }

        if ($this->width || $this->height) {
            $this->resizeImage();
        }

        $this->image->save();
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
        $this->image->crop($cropperData->width, $cropperData->height, $cropperData->x, $cropperData->y);
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
