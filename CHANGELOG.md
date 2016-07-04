# Change Log
All notable changes to this project will be documented in this file.

## 2.0.0 - 2016-07-04
### Added
- PHP 7 return type hints
- PHP 7 scalar type hints
- `WidgetManager` which provides option for having custom widgets
- Hooked [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) into the Travis CI.

### Backwards-incompatible changes
- PHP 7 is now required to use Disqus Helper
- `Disqus` instance can now only be created using `create()` named constructor
- `Disqus` configuration can only be supplied through the new `configure()` method
- `Disqus::__invoke()` replaced by `getCode()` and `__toString()` methods

## 1.2.x
This release is abandoned, please consider upgrading to 2.0.x.

[Unreleased]: https://github.com/nikolaposa/disqus-helper/compare/1.2.x...HEAD