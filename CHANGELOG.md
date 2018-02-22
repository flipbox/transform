# Changelog
All Notable changes to `flipboxdigital\transform` will be documented in this file

## Unreleased
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
