# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.1.1 - 2016-09-08

### Added

- [zendframework/zend-developer-tools#217](https://github.com/zendframework/ZendDeveloperTools/pull/217) adds
  support in the `SerializableException` for PHP 7 Throwables, including Error
  types.
- [zendframework/zend-developer-tools#220](https://github.com/zendframework/ZendDeveloperTools/pull/220) adds
  support for displaying matched route parameters other than just the controller
  and action.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-developer-tools#215](https://github.com/zendframework/ZendDeveloperTools/pull/215) replaces
  the Laminas logo to remove the "2".
- [zendframework/zend-developer-tools#218](https://github.com/zendframework/ZendDeveloperTools/pull/218) updates
  the logic for retrieving a laminas-db `Adapter` to only do so if `db`
  configuration also exists; this ensures the toolbar does not cause a fatal
  error if laminas-db is installed but no adapter configured.

## 1.1.0 - 2016-06-27

### Added

- [zendframework/zend-developer-tools#213](https://github.com/zendframework/ZendDeveloperTools/pull/213) adds
  support for laminas-mvc, laminas-eventmanager, and laminas-servicemanager v3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.0 - 2016-06-27

First stable release.
