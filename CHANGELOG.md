# Release Notes

## [v2.0.3](https://github.com/ctessier/nova-advanced-image-field/compare/v2.0.2...v2.0.3)

> Released 2023/03/08

This release fixes a blocker issue on Windows servers where Intervention would throw `Encoding format (tmp) is not supported.`.

### New Features

- Add method to specify the quality of the transformed image, from 0 to 100 ([#103](https://github.com/ctessier/nova-advanced-image-field/pull/103))

### Fixed

- Fix `Encoding format (tmp) is not supported.` issue on Windows server ([#102](https://github.com/ctessier/nova-advanced-image-field/pull/102)). Thanks [dimalebid](https://github.com/dimalebid) for your help!
- Fix issue when using the field on something other than a Nova Resource ([#99](https://github.com/ctessier/nova-advanced-image-field/pull/99)). Thanks [Jaspur](https://github.com/Jaspur)!

## [v2.0.2](https://github.com/ctessier/nova-advanced-image-field/compare/v2.0.1...v2.0.2)

> Released 2023/02/16

This release fixes a blocker issue introduced with Nova v4.22.0.

### Fixed

- Fix issue when the crop function was called with float values ([#86](https://github.com/ctessier/nova-advanced-image-field/pull/86)). Thanks [Woeler](https://github.com/Woeler)!
- Fix issue when the field was used without the cropper ([#89](https://github.com/ctessier/nova-advanced-image-field/pull/89)). Thanks [Woeler](https://github.com/Woeler)!
- Fix file handler not being triggered anymore since Nova v4.22.0 ([#97](https://github.com/ctessier/nova-advanced-image-field/pull/97)). Thanks [puzzledmonkey](https://github.com/puzzledmonkey)!

## [v2.0.1](https://github.com/ctessier/nova-advanced-image-field/compare/v2.0.0...v2.0.1)

> Released 2022/12/13

### Changed

- Remove `console.log` ([#85](https://github.com/ctessier/nova-advanced-image-field/pull/85)). Thanks [puzzledmonkey](https://github.com/puzzledmonkey)!

## [v2.0.0](https://github.com/ctessier/nova-advanced-image-field/compare/v1.3.3...v2.0.0)

> Released 2022/12/12

### 🎉 Support for Nova 4 is here!

Advanced Image Field is now compatible with Nova 4! This version brings **no breaking change**, so you can keep your existing AdvancedImage fields unchanged while migrating to Nova 4.

👏 A special thanks to [Grafikart](https://github.com/Grafikart), as well as James Hilton and my other sponsors for their contribution!

### Changed

- Nova 4 compatibility
- The cropper now uses [Advanced Cropper](https://advanced-cropper.github.io/vue-advanced-cropper/) instead of Cropper.js

## [v1.3.3](https://github.com/ctessier/nova-advanced-image-field/compare/v1.3.2...v1.3.3)

> Released 2022/05/28

### Fixed

- Fix delete modal opening only for the last item when using multiple image fields ([#81](https://github.com/ctessier/nova-advanced-image-field/pull/81)). Thanks [nilsvannoort](https://github.com/nilsvannoort)!

## [v1.3.2](https://github.com/ctessier/nova-advanced-image-field/compare/v1.3.1...v1.3.2)

> Released 2022/04/19

### Changed

- Fix documentation
- Add Sponsor and funding links

## [v1.3.1](https://github.com/ctessier/nova-advanced-image-field/compare/v1.3.0...v1.3.1)

> Released 2022/02/17

### Fixed

- Fix issue when calling `Storage::delete` with `null` on Laravel 9 ([#74](https://github.com/ctessier/nova-advanced-image-field/pull/74)). Thanks [Woeler](https://github.com/Woeler)!

## [v1.3.0](https://github.com/ctessier/nova-advanced-image-field/compare/v1.2.1...v1.3.0)

> Released 2021/05/16

### New Features

- Introduce the `AdvancedAvatar` class to use the image as an avatar / cover art in search results ([@ctessier](https://github.com/ctessier) in [#65](https://github.com/ctessier/nova-advanced-image-field/pull/65)).
- Implement auto-orientation from image Exif data ([@rbnhtl](https://github.com/rbnhtl) in [#61](https://github.com/ctessier/nova-advanced-image-field/pull/61)).

### Changed

- Improve documentation
- Remove unused CSS files
- Added an `alt` attribute on the index vue's image
- Bump [Cropper.js](https://github.com/fengyuanchen/cropperjs) dependency to v1.5.11

## [v1.2.1](https://github.com/ctessier/nova-advanced-image-field/compare/v1.2.0...v1.2.1)

> Released 2020/10/25

### Fixed

- Fix issue with the field's help text ([@mziraki](https://github.com/mziraki) in [#57](https://github.com/ctessier/nova-advanced-image-field/pull/57)).

### Changed

- Bump [Cropper.js](https://github.com/fengyuanchen/cropperjs) dependency to v1.5.9

## [v1.2.0](https://github.com/ctessier/nova-advanced-image-field/compare/v1.1.0...v1.2.0)

> Released 2020/08/29

### 🎉 `AdvancedImage` now fully inherits from the Nova `Image` native field!

Even though this version is a minor one, it's major in terms of what it brings. And with **no breaking change!**

Many of you who use this package complained that you can't use basic `File`'s methods such as `store` or `storeAs` or `storeOriginalName`.

**Well... this is now possible!** `AdvancedImage` now fully inherits from `Image` (which inherits from `File`) so you can customize your image uploads the way you need, with cropping and resizing!

Here is a list of all the new available methods. Check out the [Nova documentation](https://nova.laravel.com/docs/3.0/resources/file-fields.html) for more details on their usage.

- `disk`
- `disableDownload`
- `storeOriginalName`
- `storeSize`
- `deletable`
- `prunable`
- `path`
- `storeAs`
- `store`
- `delete`
- `preview`
- `thumbnail`
- `squared`
- `acceptedTypes`

I hope your guys will enjoy this new release and keep giving me feedbacks on how to improve the package!

## [v1.1.0](https://github.com/ctessier/nova-advanced-image-field/compare/v1.0.2...v1.1.0)

> Released 2020/06/07

### Added

- Possibility to choose between `gd` or `imagick` image processing library ([#43](https://github.com/ctessier/nova-advanced-image-field/pull/43)).
- License file ([#37](https://github.com/ctessier/nova-advanced-image-field/pull/37)). Thanks [james-staffs](https://github.com/james-staffs)!

### Internal

- Upgrade package dependencies ([#40](https://github.com/ctessier/nova-advanced-image-field/pull/40)).

## [v1.0.2](https://github.com/ctessier/nova-advanced-image-field/compare/v1.0.1...v1.0.2)

> Released 2020/03/15

### Added

- This very CHANGELOG file

### Fixed

- Fix use of `->path()` to specify a custom subdirectory ([#29](https://github.com/ctessier/nova-advanced-image-field/pull/29)). Thanks [mathd](https://github.com/mathd)!

## [v1.0.1](https://github.com/ctessier/nova-advanced-image-field/compare/v1.0.0...v1.0.1)

> Released 2019/02/26

### Added

- Improve localization ([#17](https://github.com/ctessier/nova-advanced-image-field/pull/17)). Thanks [mziraki](https://github.com/mziraki)!

## [v1.0.0](https://github.com/ctessier/nova-advanced-image-field/compare/v0.2.0...v1.0.0)

> Released 2019/02/15

### Changed

- Use of `previewUrl` instead of `thumbnailUrl` in the Detail and Form component ([#12](https://github.com/ctessier/nova-advanced-image-field/pull/12)).

## [v0.2.0](https://github.com/ctessier/nova-advanced-image-field/compare/v0.1.1...v0.2.0)

> Released 2019/02/15

### Fixed

- Fix 409 Conflict when deleting the file ([#5](https://github.com/ctessier/nova-advanced-image-field/pull/5)).

## [v0.1.1](https://github.com/ctessier/nova-advanced-image-field/compare/v0.1.0...v0.1.1)

> Released 2018/11/30

### Added

- Add S3 filesystem upload support ([#1](https://github.com/ctessier/nova-advanced-image-field/pull/1)).
