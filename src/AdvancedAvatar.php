<?php

namespace Ctessier\NovaAdvancedImageField;

use Laravel\Nova\Contracts\Cover;

class AdvancedAvatar extends AdvancedImage implements Cover
{
    /**
     * Indicates whether the image should be fully rounded or not.
     *
     * @var bool
     */
    public $rounded = true;
}
