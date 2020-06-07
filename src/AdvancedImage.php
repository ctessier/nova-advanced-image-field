<?php

namespace Ctessier\NovaAdvancedImageField;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class AdvancedImage extends File
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'advanced-image-field';

    /**
     * The driver library to use for image manipulation.
     *
     * This value will override the driver configured for Intervention
     * in the `config/image.php` file of the Laravel project.
     *
     * @var string|null
     */
    public $driver = null;

    /**
     * Indicates if the image is croppable.
     *
     * @var bool
     */
    public $croppable = false;

    /**
     * The fixed aspect ratio of the crop box.
     *
     * @var float
     */
    public $cropAspectRatio;

    /**
     * The width for the resizing of the image.
     *
     * @var int
     */
    public $width;

    /**
     * The height for the resizing of the image.
     *
     * @var int
     */
    public $height;

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * Create a new field.
     *
     * @param string        $name
     * @param string|null   $attribute
     * @param string|null   $disk
     * @param callable|null $storageCallback
     *
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->thumbnail(function () {
            return $this->value ? Storage::disk($this->disk)->url($this->value) : null;
        })->preview(function () {
            return $this->value ? Storage::disk($this->disk)->url($this->value) : null;
        });
    }

    /**
     * Override the default driver to be used by Intervention for the image manipulation.
     *
     * @param string $driver
     *
     * @return $this
     *
     * @throws \Exception
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
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param string                                  $requestAttribute
     * @param object                                  $model
     * @param string                                  $attribute
     *
     * @return void
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (empty($request->{$requestAttribute})) {
            return;
        }

        $previousFileName = $model->{$attribute};

        if (!$this->croppable && !$this->width && !$this->height) {
            parent::fillAttribute($request, $requestAttribute, $model, $attribute);
        } else {
            if ($this->driver) {
                Image::configure([
                    'driver' => $this->driver,
                ]);
            }
            $image = Image::make($request->{$this->attribute});
            if ($this->croppable) {
                $this->handleCrop($image, json_decode($request->{$this->attribute.'_data'}));
            }
            if ($this->width || $this->height) {
                $this->handleResize($image, $this->width, $this->height);
            }

            $image->stream();
            if ($this->storagePath === '/') {
                $fileName = $request->{$this->attribute}->hashName();
            } else {
                $fileName = trim($this->storagePath, '/').'/'.$request->{$this->attribute}->hashName();
            }
            Storage::disk($this->disk)->put($fileName, $image->__toString());
            $image->destroy();

            $model->{$attribute} = $fileName;
        }

        Storage::disk($this->disk)->delete($previousFileName);
    }

    /**
     * Crop the uploaded image.
     *
     * @param \Intervention\Image\Image $image
     * @param object                    $cropperData
     *
     * @return void
     */
    private function handleCrop($image, $cropperData)
    {
        $image->crop($cropperData->width, $cropperData->height, $cropperData->x, $cropperData->y);
    }

    /**
     * Resize the uploaded image.
     *
     * @param \Intervention\Image\Image $image
     * @param int|null                  $width
     * @param int|null                  $height
     *
     * @return void
     */
    private function handleResize($image, $width, $height)
    {
        $image->resize($width, $height, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        });
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'croppable'   => $this->croppable,
            'aspectRatio' => $this->cropAspectRatio,
        ], parent::meta());
    }
}
