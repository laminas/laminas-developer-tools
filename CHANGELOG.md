# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.0.3 - 2020-11-02


-----

### Release Notes for [2.0.3](https://github.com/laminas/laminas-developer-tools/milestone/2)

next bug fix release (mini)

### 2.0.3

- Total issues resolved: **0**
- Total pull requests resolved: **2**
- Total contributors: **2**

#### Bug

 - [34: Improve PHPDoc return- and paramter-types](https://github.com/laminas/laminas-developer-tools/pull/34) thanks to @Gounlaf

#### Enhancement

 - [31: Fixes #1 : Toolbar should be displayed even without &lt;head&gt; in HTML5](https://github.com/laminas/laminas-developer-tools/pull/31) thanks to @samsonasik

## 2.0.2 - 2020-03-29

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Fixed `replace` version constraint in composer.json so repository can be used as replacement of `zendframework/zend-developer-tools:^2.0.0`.

## 2.0.1 - 2020-03-21

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#28](https://github.com/laminas/laminas-developer-tools/pull/28) fixes console errors after switching to `symfony/var-dumper`.

## 2.0.0 - 2019-12-26

### Added

- Nothing.

### Changed

- [zendframework/zend-developer-tools#200](https://github.com/zendframework/zend-developer-tools/pull/200) changes the `Laminas\DeveloperTools\Collector\CollectorInterface::collectEvent()` method to typehint its second argument against `Laminas\EventManager\EventInterface` instead of `Laminas\EventManager\Event`. This allows for any `EventInterface` implementation, instead of artificially restricting to those events that implement the concrete type. As such, any collectors that are implementing that interface MUST be updated to the new signature.

- [zendframework/zend-developer-tools#200](https://github.com/zendframework/zend-developer-tools/pull/200) updates the `MemoryCollector` and `TimeCollector` to the new `CollectorInterface::collectEvent()` signature.

- [zendframework/zend-developer-tools#200](https://github.com/zendframework/zend-developer-tools/pull/200) updates the `EventLoggingListenerAggregate::onCollectEvent()` method to typehint against `Laminas\EventManager\EventInterface` instead of `Laminas\EventManager\Event`. This change should only affect extensions to the class.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.3.1 - 2020-03-21

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#28](https://github.com/laminas/laminas-developer-tools/pull/28) fixes console errors after switching to `symfony/var-dumper`.

## 1.3.0 - 2019-12-26

### Added

- Nothing.

### Changed

- [zendframework/zend-developer-tools#261](https://github.com/zendframework/zend-developer-tools/pull/261) swaps in symfony/var-dumper for zendframework/zend-debug, as the latter is now archived and abandoned.

- [zendframework/zend-developer-tools#262](https://github.com/zendframework/zend-developer-tools/pull/262) moves the file `src/Controller/DeveloperToolsController.php` to `src/Controller/IndexController.php`, so that the filename matches the class name it defines.

- [zendframework/zend-developer-tools#262](https://github.com/zendframework/zend-developer-tools/pull/262) moves the file `src/Match/MatchInterface.php` to `src/MatchInterface.php`, so that the filename matches the class name it defines.

- [zendframework/zend-developer-tools#262](https://github.com/zendframework/zend-developer-tools/pull/262) moves the file `test/Collector/ConfigCollectionTest.php` to `src/Collector/ConfigCollectorTest.php`, so that the filename matches the class name it defines.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.2.4 - 2019-12-26

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-developer-tools#259](https://github.com/zendframework/zend-developer-tools/pull/259) adjusts the casing used when retrieving the "config" service within the `ConfigCollector` to be all lowercase, ensuring it works with all versions of laminas-mvc.

## 1.2.3 - 2019-03-28

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Fixes an information disclosure vulnerability; see https://framework.zend.com/security/advisory/ZF2019-01
  for more details.

## 1.2.2 - 2019-03-05

### Added

- [zendframework/zend-developer-tools#253](https://github.com/zendframework/zend-developer-tools/pull/253) and [zendframework/zend-developer-tools#254](https://github.com/zendframework/zend-developer-tools/pull/254) add support for PHP 7.3.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.2.1 - 2018-04-18

### Added

- Nothing.

### Changed

- [zendframework/zend-developer-tools#249](https://github.com/zendframework/zend-developer-tools/pull/249) changes the repository name to "laminas-developer-tools", in order to make the name
  consistent with other repositories, as well as play nicely with existing tooling. The Packagist
  entry has been updated to point to the new repository name, and GitHub auto-redirects any
  references to the old name to the new repository, making this a non-BC-breaking change.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-developer-tools#248](https://github.com/zendframework/zend-developer-tools/pull/248) fixes dependency configuration within the `Module` class to replace
  incorrect class and service name references.

## 1.2.0 - 2018-04-17

### Added

- [zendframework/zend-developer-tools#242](https://github.com/zendframework/zend-developer-tools/pull/242) adds support for PHP 7.1 and 7.2.

### Changed

- [zendframework/zend-developer-tools#235](https://github.com/zendframework/zend-developer-tools/pull/235) modifies the module bootstrap to defer retrieval of services until they are needed.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-developer-tools#240](https://github.com/zendframework/zend-developer-tools/pull/240) fixes an issue with slide-in of the toolbar when resizing the browser window.

- [zendframework/zend-developer-tools#231](https://github.com/zendframework/zend-developer-tools/pull/231) ensures literal `$` characters are escaped within toolbar content.

## 1.1.1 - 2016-09-08

### Added

- [zendframework/zend-developer-tools#217](https://github.com/zendframework/zend-developer-tools/pull/217) adds
  support in the `SerializableException` for PHP 7 Throwables, including Error
  types.
- [zendframework/zend-developer-tools#220](https://github.com/zendframework/zend-developer-tools/pull/220) adds
  support for displaying matched route parameters other than just the controller
  and action.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-developer-tools#215](https://github.com/zendframework/zend-developer-tools/pull/215) replaces
  the Laminas logo to remove the "2".
- [zendframework/zend-developer-tools#218](https://github.com/zendframework/zend-developer-tools/pull/218) updates
  the logic for retrieving a laminas-db `Adapter` to only do so if `db`
  configuration also exists; this ensures the toolbar does not cause a fatal
  error if laminas-db is installed but no adapter configured.

## 1.1.0 - 2016-06-27

### Added

- [zendframework/zend-developer-tools#213](https://github.com/zendframework/zend-developer-tools/pull/213) adds
  support for laminas-mvc, laminas-eventmanager, and laminas-servicemanager v3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.0 - 2016-06-27

First stable release.
