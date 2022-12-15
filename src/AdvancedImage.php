<?php

namespace Ctessier\NovaAdvancedImageField;

use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class AdvancedImage extends Image
{
    use TransformableImage;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'advanced-image-field';

    /**
     * Preserve the old image when updating.
     *
     * @var bool
     */
    public $preserveImageWhenUpdating = false;

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

        $this->transformImage($request->{$this->attribute}, json_decode($request->{$this->attribute.'_data'}));

        parent::fillAttribute($request, $requestAttribute, $model, $attribute);

        if ($previousFileName !== null && !$this->preserveImageWhenUpdating) {
            Storage::disk($this->disk)->delete($previousFileName);
        }
    }

    /**
     * Prepare the field element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'croppable'   => $this->croppable,
            'aspectRatio' => $this->cropAspectRatio,
        ]);
    }

    /**
     * Configure if the old image should be preserved when updating.
     *
     * @param bool $preserve
     *
     * @return self
     */
    public function preserveImageWhenUpdating(bool $preserve = true): self
    {
        $this->preserveImageWhenUpdating = $preserve;

        return $this;
    }
}
