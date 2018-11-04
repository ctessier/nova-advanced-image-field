<?php

namespace Ctessier\NovaAdvancedImageField;

use Laravel\Nova\Fields\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Intervention\Image\ImageManagerStatic as Image;

class AdvancedImage extends File
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'advanced-image-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
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
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttribute(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        Image::configure(array('driver' => 'imagick'));

        // and you are ready to go ...
        $data_key = $this->attribute . '_data';
        $data = json_decode($request->{$data_key});
        $img = Image::make($request->{$this->attribute})->crop($data->width, $data->height, $data->x, $data->y);
        $img->stream(); // <-- Key point
        Storage::disk('local')->put('public/test.jpg', $img, 'public');
        $img->destroy();
        $result = 'test.jpg';

        //parent::fillAttribute($request, $requestAttribute, $model, $attribute);
        if (! is_array($result)) {
            return $model->{$attribute} = $result;
        }

        foreach ($result as $key => $value) {
            $model->{$key} = $value;
        }
    }
}
