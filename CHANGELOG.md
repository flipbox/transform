# Changelog
All Notable changes to `flipboxdigital\transform` will be documented in this file

## 3.0.0 - 2018-07-11
### Changed
- Refactored many aspect of this package.  The core concept remains the same, but code has been trimmed and, assumptions have been removed and it's much more flexible and easy to use.

## 2.2.0 - 2018-06-11
### Added
- `Scope::parseNestedValue` should be used when parsing nested values as it will handle includes, excludes gracefully 
- The concept of simple item and collection transformers which do not support advanced scope-related features

## 2.1.1 - 2018-04-24
### Added
- ‘extra’ attribute types that are an array are only verified if a type is specified

## 2.1.0 - 2018-04-10
### Added
- Allowing ‘extra’ attributes to be passed when transforming

## 2.0.0 - 2018-02-22
### Added
- Compatibility with PHP 7.2

### Changed
- Helper classes are not appended with 'Helper'.

## 1.1.0 - 2018-01-03
### Changed
- Introducing Mapper trait and helper for assisting in switching transformed array keys

## 1.0.3 - 2017-08-29
### Changed
- Transformer traits now enforce strict Scope params

## 1.0.2 - 2017-08-28
### Fixed
- Issue where `Scope::parseValue` attempt to call anonymous function on a string. Ref: [#1](https://github.com/flipbox/transform/issues/1)

## 1.0.1 - 2017-07-06
### Changed
- transformer `ObjectToArray` trait to accept the property data type when normalizing

## 1.0.0 - 2017-04-04

### Added
- Initial release!
