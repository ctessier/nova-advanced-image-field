# Nova Advanced Image Field

[![StyleCI](https://github.styleci.io/repos/156091175/shield?branch=1.x)](https://github.styleci.io/repos/156091175)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)
[![Total Downloads](https://img.shields.io/packagist/dm/ctessier/nova-advanced-image-field.svg?style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)
[![License](https://img.shields.io/github/license/ctessier/nova-advanced-image-field?color=%23B2878B&style=flat-square)](https://packagist.org/packages/ctessier/nova-advanced-image-field)

This package provides an advanced image field for Nova resources allowing you to upload, crop and resize any image.

It uses [Cropper.js](https://fengyuanchen.github.io/cropperjs) with [vue-cropperjs](https://github.com/Agontuk/vue-cropperjs) on the client and [Intervention Image](http://image.intervention.io) on the server.

![screenshot of the advanced image field](https://github.com/ctessier/nova-advanced-image-field/blob/1.x/screenshot.png?raw=true)

## Requirements

In order to work, this package requires the following components:
- Laravel & Nova (2 or 3)
- Fileinfo Extension

And **one of** the following libraries:
- GD Library >=2.0 (default)
- Imagick PHP extension >=6.5.7

See [Intervention requirements](https://image.intervention.io/v2/introduction/installation) for more details.

## Getting started

Install the package into a Laravel application with Nova using Composer:

```bash
composer require ctessier/nova-advanced-image-field
```

If you want to use Imagick as the default image processing library, follow the [Intervention documentation for Laravel](https://image.intervention.io/v2/introduction/installation#laravel).
This will provide you with a new configuration file where you can specify the driver you want.

## Code examples

`AdvancedImage` extends from `Image` so you can use any methods that `Image` implements. See the documentation [here](https://nova.laravel.com/docs/3.0/resources/file-fields.html).

```php
// Show a cropbox with a fixed ratio
AdvancedImage::make('Photo')->croppable(16/9),

// Resize the image to a max width
AdvancedImage::make('Photo')->resize(1920),

// Override the image processing driver for this field only
AdvancedImage::make('Photo')->driver('imagick'),
```

To serve the image as an avatar / cover art search results, use the `AdvancedAvatar` class:

```php
AdvancedAvatar::make('Avatar')->croppable(),
```

## API

### `driver(string $driver)`

Override the default driver to be used by Intervention for the image manipulation.

```php
AdvancedImage::make('Photo')->driver('imagick'),
```

### `croppable([float $ratio])`

Specify if the underlying image should be croppable.

If a numeric value is given as a first parameter, it will be used to define a fixed aspect ratio for the crop box.

```php
AdvancedImage::make('Photo')->croppable(),
AdvancedImage::make('Photo')->croppable(16/9),
```

### `resize(int $width = null[, int $height = null])`

Specify the size (width and height) the image should be resized to.

```php
AdvancedImage::make('Photo')->resize(1920),
AdvancedImage::make('Photo')->resize(600, 400),
AdvancedImage::make('Photo')->resize(null, 300),
```

*Note: this method uses [Intervention Image `resize()`](https://image.intervention.io/v2/api/resize) with the upsize and aspect ratio constraints.*

### `autoOrientate()`

Specify if the underlying image should be orientated.

Rotate the image to the orientation specified in Exif data, if any.

```php
AdvancedImage::make('Photo')->autoOrientate(),
```

*Note: PHP must be compiled in with `--enable-exif` to use this method. Windows users must also have the mbstring extension enabled. See [the Intervention Image documentation](https://image.intervention.io/v2/api/orientate) for more details.*
