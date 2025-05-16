# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.1.1] - 2025-05-16
### Changed
- Improved `groupRows` return type to clarify that the generator yields non-empty lists.

## [2.1.0] - 2023-09-13
### Added
- `groupRows` now supports grouping by multiple columns.
- Integer array keys can now be used for row grouping.

### Changed
- PHP 8.1+ is now required.

## [2.0.0] - 2023-02-08
### Added
- New set of methods for retrieving and validating array key types:
  - `requireStrKey`
  - `getOptionalStrKey`
  - `requireNumericKey`
  - `getOptionalNumericKey`
  - `requireIntKey`
  - `getOptionalIntKey`
  - `requireBoolKey`
  - `getOptionalBoolKey`

### Changed
- Moved all functions to static methods in an `ArrayUtils` class for easier usage.
- PHP 7.4+ is now required.

## [1.1.2] - 2022-03-16
### Changed
- Added template to `group_rows` so static analyzers can determine the return type.
- PHP 7.3+ is now required.

## [1.1.1] - 2020-03-18
### Changed
- Implemented native parameter and return type declarations.
- PHP 7.2+ is now required.

## [1.1.0] - 2016-05-24
### Added
- `group_rows` now supports `Traversable` objects in addition to arrays.

## [1.0.1] - 2016-01-14
### Fixed
- `group_rows` now correctly handles rows containing a `false` group column value.

## [1.0.0] - 2016-01-13
- Initial release

[2.1.1]: https://github.com/theodorejb/array-utils/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/theodorejb/array-utils/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/theodorejb/array-utils/compare/v1.1.2...v2.0.0
[1.1.2]: https://github.com/theodorejb/array-utils/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/theodorejb/array-utils/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/theodorejb/array-utils/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/theodorejb/array-utils/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/theodorejb/array-utils/tree/v1.0.0
