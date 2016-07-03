# Change Log
All notable changes to this project will be documented in this file.

## 2.0.0 - [Unreleased]
### Added
- PHP 7 return type hints
- PHP 7 scalar type hints
- Hooked [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) into the Travis CI.
- `WidgetManager` which provides option for having custom widgets

### Backwards-incompatible changes
- PHP 7 is now required to use Disqus Helper
- `Disqus` instance can now only be created using `create()` named constructor
- `__invoke()` of the `Disqus` class replaced by `getCode()` and `__toString()` methods

## 1.2.x
This release is abandoned, please consider upgrading to 2.0.x.

[Unreleased]: https://github.com/nikolaposa/version/compare/2.0.0...HEAD