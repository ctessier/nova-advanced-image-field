# Nova Advanced Image Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)
[![Total Downloads](https://img.shields.io/packagist/dt/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)

This package provides an advanced image field for Nova resources allowing you to upload, crop and resize any image.

It uses [Cropper.js](https://fengyuanchen.github.io/cropperjs) with [vue-cropperjs](https://github.com/Agontuk/vue-cropperjs) in the frontend and GD/Imagick and [Intervention Image](http://image.intervention.io) in the backend.

![screenshot of the advanced image field](https://github.com/ctessier/nova-advanced-image-field/blob/develop/screenshot.png?raw=true)

## Requirements

- Laravel & Nova
- Fileinfo Extension
- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)

## Installation

Install the package into a Laravel application with Nova using Composer:

```bash
composer require ctessier/nova-advanced-image-field
```

## Usage

`AdvancedImage` extends from `File` so you can use any methods that `File` implements. See the documentation [here](https://nova.laravel.com/docs/1.0/resources/file-fields.html).

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

            // Store to AWS S3
            AdvancedImage::make('photo')->disk('s3'),
        ];
    }
}
```

The `resize` option uses [Intervention Image `resize()`](http://image.intervention.io/api/resize) with the upsize and aspect ratio constraints.
