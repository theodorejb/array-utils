# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2023-02-08
### Added
- New set of functions for retrieving and validating array key types:
  - `require_str_key`
  - `get_optional_str_key`
  - `require_numeric_key`
  - `get_optional_numeric_key`
  - `require_int_key`
  - `get_optional_int_key`
  - `require_bool_key`
  - `get_optional_bool_key`

### Changed
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

[Unreleased]: https://github.com/theodorejb/array-utils/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/theodorejb/array-utils/compare/v1.1.2...v1.2.0
[1.1.2]: https://github.com/theodorejb/array-utils/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/theodorejb/array-utils/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/theodorejb/array-utils/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/theodorejb/array-utils/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/theodorejb/array-utils/tree/v1.0.0
