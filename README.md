# Nova Advanced Image Field

[![StyleCI](https://github.styleci.io/repos/156091175/shield?branch=1.x)](https://github.styleci.io/repos/156091175)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)
[![Total Downloads](https://img.shields.io/packagist/dm/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)
[![License](https://img.shields.io/github/license/ctessier/nova-advanced-image-field?color=%23B2878B&style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)

This package provides an advanced image field for Nova resources allowing you to upload, crop and resize any image.

It uses [Cropper.js](https://fengyuanchen.github.io/cropperjs) with [vue-cropperjs](https://github.com/Agontuk/vue-cropperjs) in the frontend and [Intervention Image](http://image.intervention.io) in the backend.

![screenshot of the advanced image field](https://github.com/ctessier/nova-advanced-image-field/blob/1.x/screenshot.png?raw=true)

## Requirements

To work correctly, this package requires the following components:
- Laravel & Nova
- Fileinfo Extension

And **one of** the following libraries:
- GD Library >=2.0 (used by default)
- Imagick PHP extension >=6.5.7

See [Intervention requirements](http://image.intervention.io/getting_started/installation) for more details.

## Installation

Install the package into a Laravel application with Nova using Composer:

```bash
composer require ctessier/nova-advanced-image-field
```

If you want to use Imagick as the default image processing library, follow the [Intervention documentation for Laravel](http://image.intervention.io/getting_started/installation#laravel).
This will provide you with a new configuration file where you can specify the driver you want.

## Usage

`AdvancedImage` extends from `File` so you can use any methods that `File` implements. See the documentation [here](https://nova.laravel.com/docs/2.0/resources/fields.html#file-field).

```php
<?php

namespace App\Nova;

// ...
use Ctessier\NovaAdvancedImageField\AdvancedImage;

class Post extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            // Simple image upload
            AdvancedImage::make('photo'),

            // Show a cropbox with a free ratio
            AdvancedImage::make('photo')->croppable(),

            // Show a cropbox with a fixed ratio
            AdvancedImage::make('photo')->croppable(16/9),

            // Resize the image to a max width
            AdvancedImage::make('photo')->resize(1920),

            // Resize the image to a max height
            AdvancedImage::make('photo')->resize(null, 1080),

            // Show a cropbox and resize the image
            AdvancedImage::make('photo')->croppable()->resize(400, 300),

            // Override the image processing driver for this field only
            AdvancedImage::make('photo')->driver('imagick')->croppable(),

            // Store to AWS S3
            AdvancedImage::make('photo')->disk('s3'),

            // Specify a custom subdirectory
            AdvancedImage::make('photo')->disk('s3')->path('image'),
        ];
    }
}
```

The `resize` option uses [Intervention Image `resize()`](http://image.intervention.io/api/resize) with the upsize and aspect ratio constraints.
